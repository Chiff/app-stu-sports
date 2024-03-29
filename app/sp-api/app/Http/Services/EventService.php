<?php


namespace App\Http\Services;

use App\Dto\Event\EventDTO;
use App\Dto\Event\MyEventsDTO;
use App\Dto\EventTeam\EventTeamDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\AS\EventAS;
use App\Http\Services\AS\UserTeamAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\TaskService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Http\Utils\DateUtil;
use App\Models\Event;
use App\Models\EventTeam;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
use App\Models\Netgrif\TaskReference;
use App\Models\Netgrif\TasksReferences;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;


class EventService
{
    private AuthenticationService $auth;
    private JsonMapper $jsonMapper;
    private WorkflowService $workflowService;
    private UserService $userService;
    private TaskService $taskService;
    private EventAS $eventAS;
    private UserTeamAS $userTeamAS;
    public NotificationService $notificationService;
    private CiselnikService $ciselnikService;


    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserService $userService,
        TaskService $taskService,
        JsonMapper $mapper,
        EventAS $eventAS,
        UserTeamAS $userTeamAS,
        CiselnikService $ciselnikService,
        NotificationService $notificationService
    )
    {
        $this->jsonMapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->eventAS = $eventAS;
        $this->userTeamAS = $userTeamAS;
        $this->notificationService = $notificationService;
        $this->ciselnikService = $ciselnikService;
    }

    public function deleteEvent($id): MessageResource
    {
        return $this->workflowService->deleteCaseUsingDELETE($id);
    }

    public function showUserEvents(): EmbededCases
    {
        $user = $this->userService->getLoggedUserUsingGET();
        $authorId = $user->id;
        return $this->workflowService->findAllByAuthorUsingPOST($authorId);
    }

    public function showAllEvents(): EmbededCases
    {
        return $this->workflowService->getAllUsingGET();
    }

    public function showOneEvent($id): CaseResource
    {
        return $this->workflowService->getOneUsingGET($id);
    }

    public function createOneEvent(Request $request): EventDTO
    {
        $dto = new EventDTO();
        $dto->user_id = auth()->id();
        $this->jsonMapper->mapObjectFromString(json_encode($request->toArray()), $dto);

        if ($dto->min_teams > $dto->max_teams) {
            throw new Exception("Maximálny počet tímov je menší ako minimálny", 500);
        }
        if ($dto->min_team_members > $dto->max_team_members) {
            throw new Exception("Maximálny počet hráčov v tíme je menší ako minimálny", 500);
        }


        $todayDate = DateUtil::now();

        if (($todayDate > $dto->registration_end)) {
            throw new Exception("Koniec registrácie je pred súčastným dátumom", 500);
        }
        if (($todayDate > $dto->event_end)) {
            throw new Exception("Koniec udalosti je pred súčastným dátumom", 500);
        }
        if (($todayDate > $dto->event_start)) {
            throw new Exception("Začiatok udalosti je pred súčastným dátumom", 500);
        }
        // TODO - 13/05/2021 - NA TOTO POZOR!
        app('db')->transaction(function () use ($dto, $request) {
            $createdEvent = $this->eventAS->createEvent($dto);

            if (!$createdEvent) {
                throw new Exception("could not create", 500);
            }

            $netId = $this->workflowService->getEventNetId();
            $title = "event";
            $netgrifEvent = $this->workflowService->createCaseUsingPOST($netId, $title);

            if (!$netgrifEvent) {
                throw new Exception("could not create netgrif event", 500);
            }

            $createdEvent->ext_id = $netgrifEvent->stringId;
            $createdEvent->save();

            $caseId = $netgrifEvent->stringId;
            //TODO backend doplnit transitionId do databazy
            $tasks = $this->taskService->searchTask(array(
                'case' => array('id' => $caseId),
                'transitionId' => '2'
            ));
            $taskId = $tasks->_embedded->tasks[0]->stringId;

            $this->taskService->assignUsingGET($taskId);

            $taskData =
                '{
                    "300": {
                        "type": "number",
                        "value": ' . $dto->max_teams . '
                    },
                    "400": {
                        "type": "number",
                        "value": ' . $dto->min_teams . '
                    },
                    "podujatie_nazov": {
                        "type": "text",
                        "value": "' . $dto->name . '"
                    }
            }';

            $this->taskService->setTaskData($taskId, $taskData);
            $this->taskService->finishUsingGET($taskId);

            $this->runTask($request->get('task_id'));

            $this->notificationService->createNotificationForEvent(
                "Podujatie <b>" . $dto->name . "</b> bolo úspešne vytvorené!",
                $createdEvent->id
            );

            $this->notificationService->createNotificationForUser(
                "Tvoje podujatie <b>" . $createdEvent->name . "</b> bolo úspešne vytvorené!",
                $createdEvent->user_id
            );

            $this->jsonMapper->mapObjectFromString($createdEvent, $dto);
        });

        return $dto;
    }

    /**
     * @return EventDTO[]
     */
    public function getPublicEvents(): array
    {
        $events = Event::where('disabled', '=', 0)->orderBy('id', 'desc')->get();
        return $this->mapEventsWithOwner($events);
    }

    public function update(int $id, Request $request): EventDTO
    {
        $user_id = auth()->id();
        $event = Event::whereId($id)->first();

        if (!$event) throw new \Exception("Podujatie neexistuje");

        $dto = new EventDTO();
        $dtoRequest = new EventDTO();

        $this->jsonMapper->mapObjectFromString($event->toJson(), $dto);

        $dt = DateUtil::now();

        // neviem či chcem aj takúto valid
//        if ($event->disabled)  throw new \Exception("Podujatie je zrušené e preto nie sú možné zmeny");
        if ($dto->event_start <= $dt && $dt <= $event->event_end) throw new \Exception("Podujatie práve prebieha a zmeny nie sú možné");
        if ($dto->event_end < $dt) throw new \Exception("Event už skončil");

        $this->jsonMapper->mapObjectFromString(json_encode($request->toArray()), $dtoRequest);

        $dateStartChange = $dtoRequest->event_start;
        $dateEndChange = $dtoRequest->event_end;
        $registrationStartChange = $dtoRequest->registration_start;
        $registrationEndChange = $dtoRequest->registration_end;

        if ($registrationStartChange > $registrationEndChange) throw new \Exception("Logická chyba dátumu");
        if ($dateStartChange > $dateEndChange) throw new \Exception("Logická chyba dátumu");
        if ($dateStartChange < $registrationEndChange) throw new \Exception("Logická chyba dátumu");

        if ($dateStartChange < $dt) throw new \Exception("Podujatie nemožno začať v minulosti");

        return app('db')->transaction(function () use ($dto, $dtoRequest, $event, $user_id, $request) {
            if ($dto->user_id == $user_id) {

                $event->name = $dtoRequest->name;
                $event->registration_start = $dtoRequest->registration_start;
                $event->registration_end = $dtoRequest->registration_end;
                $event->event_start = $dtoRequest->event_start;
                $event->event_end = $dtoRequest->event_end;
                $event->description = $dtoRequest->description;
                $event->type = $this->ciselnikService->getOrCreateCiselnik($dtoRequest->type, 'EVENT_TYPE')->id;

                $succ = $event->save();
                if (!$succ) {
                    throw new \Exception("Nepodarilo sa upraviť podujatie");
                }

            } else throw new \Exception("Nie si vlastníkom podujatia");

            $caseId = $event->ext_id;
            $netgrif_editEvent_transId = 96;

            $tasks = $this->taskService->searchTask(array(
                'case' => array('id' => $caseId),
                'transitionId' => $netgrif_editEvent_transId
            ));
            $taskId = $tasks->_embedded->tasks[0]->stringId;

            $this->taskService->assignUsingGET($taskId);

            $taskData =
                '{
                    "podujatie_nazov": {
                        "type": "text",
                        "value": "' . $request->get('name') . '"
                    }
            }';

            $this->taskService->setTaskData($taskId, $taskData);
            $this->taskService->finishUsingGET($taskId);

            $UpdatedEventDTO = new EventDTO();
            $UpdatedEventDTO = $this->mapEventWithOwner($event, $UpdatedEventDTO);
            $this->jsonMapper->mapObjectFromString($event->toJson(), $UpdatedEventDTO);

            //notifikacia pre event
            $this->notificationService->createNotificationForEvent(
                "Toto podujatie bolo aktualizované!",
                $event->id
            );

            //notifikacia pre prihlasene timy
            foreach ($event->teams as $team) {
                $this->notificationService->createNotificationForTeam(
                    "Podujatie <b>" . $event->name .
                    "</b>, na ktoré je tím <b>" . $team->team_name .
                    "</b> prihlásený, bolo aktualizované.",
                    $team->id
                );
            }

            return $UpdatedEventDTO;
        });
    }

    public function getMyEvents(bool $onlyActive = true): MyEventsDTO
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);

        $response = new MyEventsDTO();

        $response->owned = $this->getOwnedEvents($user, true);
        $response->ended = $this->getOwnedEvents($user, false);
        $response->upcoming = $this->getUpcomingEvents($user, $onlyActive);

        return $response;
    }

    /**
     * @param User $user
     * @param bool $onlyActive
     * @return EventDTO[]
     */
    private function getOwnedEvents(User $user, $onlyActive = true): array
    {
        $events = $user->ownEvents();

        if ($onlyActive) {
            $events->where('disabled', '=', 0)->orderBy('id', 'desc');
        } else {
            $events->where('disabled', '=', 1)->orderBy('id', 'desc');
        }

        return $this->mapEventsWithOwner($events->get());
    }

    /**
     * @param User $user
     * @param bool $onlyActive
     * @return EventDTO[]
     */
    private function getUpcomingEvents(User $user, $onlyActive = true): array
    {
        $teams = UserTeam::whereUserId($user->id)->select('team_id');
        $eventTeam = EventTeam::select('event_id')->whereIn('team_id', $teams);
        $events = Event::select()->where('disabled', '=', 0)->whereIn('id', $eventTeam->select('event_id'))->orderBy('id', 'desc');

        $qr = $events->get();
        $col = new Collection();

        foreach ($qr as $e) {
            if (!($e instanceof Event)) continue;

            // ak nema vitaza neskoncilo
            $hasWinner = $e->teams()->where('is_winner', '=', true)->count() > 0;
            if (!$hasWinner) {
                $col->add($e);
            }
        }

        return $this->mapEventsWithOwner($col);
    }


    private function mapEventWithOwner(Event $model, EventDTO $dto = null): EventDTO
    {
        $eventDTO = new EventDTO();

        // v pripade ze nechceme vytvorit novy ale len doplnit uz existujuci
        if ($dto) {
            $eventDTO = $dto;
        }

        $this->jsonMapper->mapObjectFromString($model->toJson(), $eventDTO);

        $user = new UserDTO();
        $userModel = User::whereId($model->user_id)->first();
        $this->jsonMapper->mapObjectFromString($userModel->toJson(), $user);

        $eventDTO->owner = $user;

        return $eventDTO;
    }

    /**
     * @param Collection $events
     * @return EventDTO[]
     */
    private function mapEventsWithOwner(Collection $events): array
    {
        $result = [];

        foreach ($events as $event) {
            $eventDTO = $this->mapEventWithOwner($event);
            array_push($result, $eventDTO);
        }

        return $result;
    }

    public function getEventActiveTasks($eventCaseId, EventDTO $dto): TasksReferences
    {
        $tasks = $this->taskService->getTasksOfCaseUsingGET($eventCaseId);


        $isOwner = $this->isEventOwner(auth()->id(), $eventCaseId);
        $hasTeamOnEvent = $this->hasTeamOnEvent(auth()->id(), $eventCaseId);

        $allowForTeamOwner = ["66"]; // odhlasenie timu
        $allowForUnknown = ["1"]; // prihlasenie timu
        $allowBeforeStart = ["5", "96"]; // zrusenie, editovanie
        $allowBeforeEnd = ["6"]; // start + pridanie bodov (ak je odstartovane)
        $allowAfterEnd = ["7"]; // vyhodnotenie + pridanie bodov (ak NIE je ukoncene)


        $result = new TasksReferences();
        $result->taskReference = [];

        $dt = DateUtil::now();
        $canRegister = $dt >= $dto->registration_start && $dt <= $dto->registration_end;
        $isBeforeStart = $dt < $dto->event_start;
        $isBeforeEnd = $dto->event_start <= $dt && $dt <= $dto->event_end;
        $isAfterEnd = $dto->event_end < $dt;

//        var_dump($canRegister);
//        var_dump($isBeforeStart);
//        var_dump($isBeforeEnd);
//        var_dump($tasks->taskReference);

        $canStartEvent = false;
        $canEndEvent = false;
        $allowAddPoints = false;
        foreach ($tasks->taskReference as $task) {

            // event skoncil + $isOwner
            if (in_array($task->transitionId, $allowAfterEnd)) {
                if ($isAfterEnd && $isOwner) {
                    array_push($result->taskReference, $task);
                }

                if ($isOwner) {
                    $allowAddPoints = true;
                    $canEndEvent = true;
                }
            }

            // event nezacal + $isOwner
            if ($isOwner && $isBeforeStart && in_array($task->transitionId, $allowBeforeStart)) {
                array_push($result->taskReference, $task);
            }

            // event startuje
            if ($isBeforeEnd && in_array($task->transitionId, $allowBeforeEnd)) {
                if ($isOwner) {
                    array_push($result->taskReference, $task);
                }

                $allowAddPoints = true;
                $canStartEvent = true;
            }

            // mam tim na evente + event nezacal
            if ($hasTeamOnEvent == true && $isBeforeStart && in_array($task->transitionId, $allowForTeamOwner)) {
                array_push($result->taskReference, $task);
            }

            // nemam tim na evente + event nezacal
            if ($hasTeamOnEvent == false && $isBeforeStart && $canRegister && in_array($task->transitionId, $allowForUnknown)) {
                array_push($result->taskReference, $task);
            }
        }

        if (!$canStartEvent && ($isBeforeEnd || $isAfterEnd)) {
            foreach ($tasks->taskReference as $task) {
                if ($isOwner && in_array($task->transitionId, ["5"])) {
                    array_push($result->taskReference, $task);
                }
            }
        }

        if (($canEndEvent|| $canStartEvent) && $allowAddPoints) {
            $tmp = new TaskReference();
            $tmp->transitionId = "999";
            $tmp->title = "pridaj bod";
            $tmp->stringId = "-1";
            array_push($result->taskReference, $tmp);
        }

        return $result;
    }

    public function hasTeamOnEvent($user_id, $eventCaseId): bool
    {
        $has_team = false;

        $userTeams = Team::whereUserId($user_id)->select('id')->get()->toArray();
        $eventTeams = Event::whereExtId($eventCaseId)->first()->teams;

        $tmp = [];
        foreach ($userTeams as $ut) {
            array_push($tmp, $ut["id"]);
        }

        foreach ($eventTeams as $eventTeam) {
            if (in_array($eventTeam->id, $tmp)) {
                return true;
            }
        }

        return $has_team;
    }

    public function isEventOwner($user_id, $eventCaseId): bool
    {
        $is_owner = false;

        $event = Event::whereExtId($eventCaseId)->first();
        if ($event->user_id == $user_id) return true;

        return $is_owner;
    }

    public function mapEventWithTeams($event, $dto): EventDTO
    {

        $dto = $this->mapEventWithOwner($event, $dto);
        $teams = [];
        foreach ($event->teams as $team) {
            $teamDTO = $this->userTeamAS->mapTeamDetail($team);
            array_push($teams, $teamDTO);
        }
        $this->jsonMapper->mapObjectFromString($event->toJson(), $dto);
        $dto->teams_on_event = $teams;

        return $dto;
    }

    public function mapEventWithTeamOnEvent($event, $dto): EventDTO
    {
        $collection = EventTeam::whereEventId($event->id)->orderBy('points', 'desc')->get();

        $eventTeams = [];
        foreach ($collection as $model) {
            $dto2 = new EventTeamDTO();
            $this->jsonMapper->mapObjectFromString($model->toJson(), $dto2);
            $dto2->team_name = Team::whereId($model->team_id)->first()->team_name;
            array_push($eventTeams, $dto2);
        }

        $dto->event_team_info = $eventTeams;
        return $dto;
    }

    public function getFullEventById($id): EventDTO
    {
        $event = Event::whereId($id)->first();

        if (!$event || !$id) {
            throw new Exception('not found', 404);
        }

        $dto = new EventDTO();
        $dto = $this->mapEventWithTeams($event, $dto);
        $dto = $this->mapEventWithTeamOnEvent($event, $dto);
        $dto->available_transitions = $this->getEventActiveTasks($event->ext_id, $dto);

        return $dto;
    }

    public function runTask($taskId): MessageResource
    {
        return $this->taskService->runTask($taskId);
    }

}
