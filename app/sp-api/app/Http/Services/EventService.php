<?php


namespace App\Http\Services;

use App\Dto\Event\EventDTO;
use App\Dto\Event\MyEventsDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\AS\EventAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\TaskService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Event;
use App\Models\EventTeam;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
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


    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserService $userService,
        TaskService $taskService,
        JsonMapper $mapper,
        EventAS $eventAS,
    )
    {
        $this->jsonMapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->eventAS = $eventAS;
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

        // TODO - 13/05/2021 - NA TOTO POZOR!
        app('db')->transaction(function () use ($dto) {
            $createdEvent = $this->eventAS->createEvent($dto);

            if (!$createdEvent) {
                throw new Exception("could not create", 500);
            }

            $netId = env('API_INTERES_EVENT_NET_ID');
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
                'transitionId' => env('API_INTERES_EVENT_CREATE_EVENT_TRANSITION')
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

            $this->jsonMapper->mapObjectFromString($createdEvent, $dto);
        });

        return $dto;
    }

    /**
     * @return EventDTO[]
     */
    public function getPublicEvents(): array
    {
        $todayDate = date('Y/m/d H:m:i');
        $events = Event::where('registration_end', '>=', $todayDate)->get();

        return $this->mapEventsWithOwner($events);
    }

    public function getMyEvents(bool $onlyActive = false): MyEventsDTO
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);

        $response = new MyEventsDTO();

        $response->owned = $this->getOwnedEvents($user, $onlyActive);
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
            $todayDate = date('Y/m/d H:m:i');
            $events->where('event_end', '>=', $todayDate);
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
        $events = Event::select()->whereIn('id', $eventTeam->select('event_id'));

        if ($onlyActive) {
            $todayDate = date('Y/m/d H:m:i');
            $events->where('event_end', '>=', $todayDate);
        }

        return $this->mapEventsWithOwner($events->get());
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

    public function getEventActiveTasks($eventCaseId): TasksReferences
    {
        $tasks = $this->taskService->getTasksOfCaseUsingGET($eventCaseId);

        $isOwner = $this->isEventOwner(auth()->id(), $eventCaseId);
        $hasTeam = $this->hasTeamOnEvent(auth()->id(), $eventCaseId);

        $adminTransIds = [5, 6, 7, 96, 57];
        $teamOwner = [66, 59];
        //($tasks);
        $iterator = 0;
        foreach ($tasks->taskReference as $task) {

            //ak uzivatel nie je vlastnikom podujatia
            if ($isOwner == false) {
                if (in_array($task->transitionId, $adminTransIds)) {
                    unset($tasks->taskReference[$iterator]);
                    $iterator++;
                    continue;
                }
            }
            //ak uzivatel nevlastni ziadny z prihlasenych timov
            if ($hasTeam == false) {
                if (in_array($task->transitionId, $teamOwner)) {
                    unset($tasks->taskReference[$iterator]);
                    $iterator++;
                    continue;
                }
            }
            $iterator++;
        }
        return $tasks;
    }

    public function hasTeamOnEvent($user_id, $eventCaseId): bool
    {
        $has_team = false;

        $userTeams = Team::whereUserId($user_id)->select('id')->get()->toArray();
        $eventTeams = Event::whereExtId($eventCaseId)->first()->teams;

        foreach ($eventTeams as $eventTeam) {
            if (in_array($eventTeam->id, $userTeams)) {
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



    public function getFullEventById($id): EventDTO
    {
        $model = Event::whereId($id)->first();

        if (!$model || !$id) {
            throw new Exception('not found', 404);
        }

        $dto = new EventDTO();
        $dto = $this->mapEventWithOwner($model, $dto);
        // TODO - 16. 5. 2021 - @msteklac/@mrybar
        // $dto = $this->mapEventWithTeams($model, $dto);

        return $dto;
    }

}
