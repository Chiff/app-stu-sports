<?php


namespace App\Http\Controllers;

use App\Dto\Event\EventDTO;
use App\Http\Services\EventService;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use JsonMapper\JsonMapper;
use Laravel\Lumen\Routing\Controller;


class EventsController extends Controller
{
    private EventService $eventService;

    // tento kod zaruci ze auth middleware nebude obmedzovat showAllEvents
    private JsonMapper $jsonMapper;

    public function __construct(EventService $event, JsonMapper $jsonMapper, array $attributes = [])
    {
        $this->eventService = $event;

        $this->middleware('auth', ['except' => ['showAllEvents']]);
        $this->jsonMapper = $jsonMapper;
    }

    /**
     * Returns all events in the system, where registration is still possible
     */
    public function showAllEvents(): JsonResponse
    {
        $events = $this->eventService->getPublicEvents();
        return response()->json($events, 200);
    }

    /**
     * Get all events of logged user
     */
    public function showUserEvents(): JsonResponse
    {
        $events = $this->eventService->getMyEvents(true);
        return response()->json($events, 200);
    }

    /**
     * Find event by event id
     * @param $id
     * @return JsonResponse
     */
    public function showOneEventById($id): JsonResponse
    {
        $event = Event::findOrFail($id);
        return response()->json($event, 200);
    }

    /**
     * Find event by event name
     * @param $event_name
     * @return JsonResponse
     */
    public function showOneEventsByEventName($name): JsonResponse
    {
        $events = Event::where('name', $name)->get();
        return response()->json($events, 200);
    }

    /**
     * Delete one event, by event id
     * @param $event_id
     * @return JsonResponse
     */
    public function deleteOneEvent($event_id): JsonResponse
    {
        //TODO kontrolovat, ci je aj vlastnikom eventu?
        Event::findOrFail($event_id)->delete();
        return response()->json('', 200);
    }

    /**
     * Create one event
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createOneEvent(Request $request): JsonResponse
    {
        $this->validate($request, EventDTO::$validationRules);
        $this->eventService->createOneEvent($request);
        return response()->json();
    }

    /**
     * Add one participant to event, by participants id
     * @param Request $request
     * @throws ValidationException
     */
    public function signTeamById(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'team_id' => 'required'
        ]);

        $event_id = $request->get('event_id');
        $team_id= $request->get('team_id');

        $event = Event::whereId($event_id)->first();
        if (!$event) {
            throw new \Exception("event not found");
        }

        $todayDate = date('Y-m-d H:m:i');

        if ($event->registration_start > $todayDate) return response()->json('Registracia ma toto podujatie ešte nebola spustená', 400);
        if ($event->registration_end < $todayDate) return response()->json('Registracia ma toto podujatie vypršala', 400);


        // Ak sa jedna o nejaky public event, kde sa prihlasuju ludia samy za seba
        // v takom pripade pocitame s tym, ze v evente je nastavene max_team_members 1
        if ($event->max_team_members == 1) {
            $user_id = auth()->id();
            $user = User::findOrFail($user_id);
            $user_name = $user->firstname . ' ' . $user->surname;

            /*
             * Check if user already have team of himself
             */
            $exists = $user->ownTeams()->where('team_name', $user_name)->get();
            if (sizeof($exists) == 0) {
                $team = new Team(array('team_name' => $user_name));
                $user->ownTeams()->save($team);
                $team->save();

                $user->teams()->attach($team);
                $user->save();
            }

            /*
             * Check if team already signed in event
             */
            $team = $user->ownTeams()->where('team_name', $user_name)->first();
            if($event->teams()->find($team->id)){
                return response()->json('Team sa uz nachadza na evente', 200);
            }
            else{
                if($event->max_teams > sizeof($event->teams()->get())) {
                    $event->teams()->save($team);
                    return response()->json('Done', 200);
                }
                else return response()->json('Kapacita eventu je uz plna', 400);

            }

        }

        $team = Team::whereId($team_id)->first();
        if (!$team) {
            throw new \Exception("team not found");
        }

        $team_members_siźe = $team->team_members()->get();

        if ($event->min_team_members > sizeof($team_members_siźe)){
            return response()->json('Team má málo členov', 404);
        }

        if ($event->max_team_members < sizeof($team_members_siźe)){
            return response()->json('Team má vela členov', 404);
        }


        if ($team->user_id != auth()->id()){
            return response()->json('Nie si vlastníkom tímu!', 404);
        }

        if($event->teams()->find($team->id)){

            return response()->json('Team sa uz nachadza na evente', 200);
        }

        else{
            if($event->max_teams > sizeof($event->teams()->get())) $event->teams()->save($team);
            else return response()->json('Kapacita eventu je uz plna', 400);

        }

        return response()->json('Done', 200);
    }

    /**
     * Add one participant to event, by participants email
     * @param Request $request
     * @throws ValidationException
     */
    public function addOneParticipantToEventByEmail(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'user_mail' => 'required|email'
        ]);

        $event_id = $request->get('event_id');
        $usermail = $request->get('user_mail');

        $event = Event::findOrFail($event_id);
        $user = User::where('email', $usermail);

        $user_id = $user->id();

        $exist = $event->participants->contains(auth()->id());
        if ($exist == null) {
            $event->participants()->attach($user_id);
            $event->save();
        }

        return response()->json('', 200);
    }

    /**
     * Update event info
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event, 200);
    }

}
