<?php


namespace App\Http\Services;

use App\Dto\Event\EventDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\AS\EventAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;
use function MongoDB\BSON\toJSON;

class EventService
{
    private AuthenticationService $auth;
    private JsonMapper $jsonMapper;
    private WorkflowService $workflowService;
    private UserService $userService;
    private EventAS $eventAS;

    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserService $userService,
        JsonMapper $mapper,
        EventAS $eventAS,
    )
    {
        $this->jsonMapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userService = $userService;
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

        // TODO - 13/05/2021 - @mrybar - doplnit spravne data do netgrifu (vid $dto)
        $netId = env('API_INTERES_EVENT_NET_ID');
        $title = "event";
        $netgrifEvent = $this->workflowService->createCaseUsingPOST($netId, $title);

        // TODO - 13/05/2021 - NA TOTO POZOR!
        app('db')->transaction(function() use ($dto, $netgrifEvent) {
            $saved = $this->eventAS->save($dto, $netgrifEvent);

            if (!$saved) {
                throw new \Exception("could not save", 500);
            }
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

        if ($onlyActive) {
            $todayDate = date('Y/m/d H:m:i');
            $events->where('event_end', '>=', $todayDate);
        }

        return $this->mapEventsWithOwner($events);
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

//            $eDto = new EventDTO();
//            foreach ($event as $keyname=>$val){
//              $eDto->{$keyname}=$val;
//            }

            $eventDTO->owner = $user;

          //  dd($eventDTO);

            array_push($result, $eventDTO);
        }

        return $result;

    }
}
