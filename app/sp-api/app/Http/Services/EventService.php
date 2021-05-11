<?php


namespace App\Http\Services;

use App\Dto\Event\EventDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
use App\Models\User;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;

class EventService
{
    private AuthenticationService $auth;
    private JsonMapper $mapper;
    private WorkflowService $workflowService;
    private UserService $userService;

    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserService $userService,
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userService = $userService;
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

    public function createOneEvent(): CaseResource
    {
        $netId = env('API_INTERES_EVENT_NET_ID');
        $title = "event";
        //TODO mozno dorobit, nech sa caseu nastavuje ako title nazov podujatia
        return $this->workflowService->createCaseUsingPOST($netId, $title);
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
        $events = $user->ownEvents();

        if ($onlyActive) {
            $todayDate = date('Y/m/d H:m:i');
            $events->where('event_end', '>=', $todayDate);
        }

        return $this->mapEventsWithOwner($events->get());
    }

    /**
     * @param Collection $events
     * @return EventDTO[]
     */
    private function mapEventsWithOwner(Collection $events): array
    {
        $result = [];

        foreach ($events as $e) {
            $eDto = new EventDTO();
            $this->mapper->mapObjectFromString($e->toJson(), $eDto);


            $user = new UserDTO();
            $userModel = User::whereId($e->user_id)->first()->toJson();
            $this->mapper->mapObjectFromString($userModel, $user);

            $eDto->owner = $user;

            array_push($result, $eDto);
        }

        return $result;

    }
}
