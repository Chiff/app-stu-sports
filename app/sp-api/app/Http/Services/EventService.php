<?php


namespace App\Http\Services;

use App\Dto\Event\EventDTO;
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
use App\Models\User;
use App\Models\UserEvent;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;
use stdClass;
use function MongoDB\BSON\toJSON;

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

    public function createOneEvent(Request $request): void
    {
        $dto = new EventDTO();
        $dto->user_id = auth()->id();
        $this->jsonMapper->mapObjectFromString(json_encode($request->toArray()), $dto);

        $netgrifEvent = null;

        // TODO - 13/05/2021 - NA TOTO POZOR!
        app('db')->transaction(function() use ($dto) {
            $createdEvent = $this->eventAS->createEvent($dto);

            if (!$createdEvent) {
                throw new \Exception("could not create", 500);
            }

            $netId = env('API_INTERES_EVENT_NET_ID');
            $title = "event";
            $netgrifEvent = $this->workflowService->createCaseUsingPOST($netId, $title);

            if(!$netgrifEvent) {
                throw new \Exception("could not create netgrif event", 500);
            }

            $createdEvent->ext_id = $netgrifEvent->stringId;
            $createdEvent->save();

            $caseId = $netgrifEvent->stringId;
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
                        "value": '.$dto->max_teams.'
                    },
                    "400": {
                        "type": "number",
                        "value": '.$dto->min_teams.'
                    },
                    "podujatie_nazov": {
                        "type": "text",
                        "value": "'.$dto->name.'"
                    }
            }';

            $this->taskService->setTaskData($taskId, $taskData);
            $this->taskService->finishUsingGET($taskId);

        });
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

    /**
     * @param bool $onlyActive
     * @return EventDTO[]
     */
    public function getOwnEvents(bool $onlyActive = false): array
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $events = $user->ownEvents()->get();

        $events_array = [];
        foreach ($events as $he){
            array_push($events_array, $he);
        }


        $myTeams = UserTeam::whereUserId($user->id)->get(); //vrati list
        $pole = [];
        foreach ($myTeams as $myTeam){
            $teamEvents = EventTeam::where('team_id', $myTeam->team_id)->get();

            if (sizeof($teamEvents) != 0){
                array_push($pole, $teamEvents);
            }
        }

        $pole2 = [];

        foreach ($pole as $pp){
            foreach ($pp as $p){
                $p->toJson();
                if (!in_array($p->event_id, $pole2)) array_push($pole2, $p->event_id);

            }
        }

        $eventss = [];
        foreach ($pole2 as $pls_koniec){
            $eventik = Event::findOrFail($pls_koniec);
            array_push($eventss,$eventik);
        }

        $array = array_unique (array_merge ($events_array, $eventss));

        foreach ($array as $a1){
            $dto = new EventDTO();
            $this->jsonMapper->mapObjectFromString(json_encode($a1->toArray()), $dto);
            echo $a1;
        }

        $col= collect($array);

        if ($onlyActive) {
            $todayDate = date('Y/m/d H:m:i');
            $col->where('event_end', '>=', $todayDate);
        }

        return $this->mapEventsWithOwner($col);
    }

    /**
     * @param Collection $events
     * @return EventDTO[]
     */
    private function mapEventsWithOwner(Collection $events): array
    {
        $result = [];

        foreach ($events as $event) {

            $eventDTO = new EventDTO();
            $this->jsonMapper->mapObjectFromString($event->toJson(), $eventDTO);

            $user = new UserDTO();
            $userModel = User::whereId($event->user_id)->first();
            $this->jsonMapper->mapObjectFromString($userModel->toJson(), $user);

            $eventDTO->owner = $user;


            array_push($result, $eventDTO);
        }


        return $result;

    }
}
