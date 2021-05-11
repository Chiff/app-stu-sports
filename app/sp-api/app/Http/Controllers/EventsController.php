<?php


namespace App\Http\Controllers;

use App\Http\Services\EventService;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;


class EventsController extends Controller
{
    private EventService $eventService;

    // tento kod zaruci ze auth middleware nebude obmedzovat showAllEvents
    public function __construct(EventService $event, array $attributes = [])
    {
        $this->eventService = $event;

        $this->middleware('auth', ['except' => ['showAllEvents']]);
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
        $events = $this->eventService->getOwnEvents(true);
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

        $netgrifEvent = $this->eventService->createOneEvent();
        $ownerId = auth()->id();
        $ext_id = $netgrifEvent->stringId;

        $this->validate($request, [
            'eventName' => 'required',
            'registration_start' => 'required|date|date_format:Y-m-d',
            'registration_end' => 'required|date|date_format:Y-m-d|',
            'event_start' => 'required|date|date_format:Y-m-d|',
            'min_teams' => 'required|integer|min:1',
            'max_teams' => 'required|integer|min:1',
            'min_team_members' => 'required|integer|min:1',
            'max_team_members' => 'required|integer|min:1'
        ]);

        $eventName = $request->get('eventName');
        $registration_start = $request->get('registration_start');
        $registration_end = $request->get('registration_end');
        $event_start = $request->get('event_start');
        $min_teams = $request->get('min_teams');
        $max_teams = $request->get('max_teams');
        $min_team_members = $request->get('min_team_members');
        $max_team_members = $request->get('max_team_members');

        $event = new Event(array('user_id' => $ownerId, 'ext_id' => $ext_id, 'name' => $eventName,
            'registration_start' => $registration_start, 'registration_end' => $registration_end,
            'event_start' => $event_start, 'min_teams' => $min_teams, 'max_teams' => $max_teams,
            'min_team_members' => $min_team_members, 'max_team_members' => $max_team_members));
        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Add one participant to event, by participants id
     * @param Request $request
     * @throws ValidationException
     */
    public function signTeamById(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required'
        ]);

        $event_id = $request->get('event_id');

        $event = Event::findOrFail($event_id);

        if($event->max_team_members == 1) {
            $user_id = auth()->id();
            $user = User::findOrFail($user_id);
            $user_name = $user->firstname . ' ' . $user->surname;

            /*
             * Check if user already have team of himself
             */
            $exists = $user->ownTeams()->where('team_name', $user_name)->get();
            if(sizeof($exists) == 0) {
                $team = new Team(array('team_name' => $user_name));
                $user->ownTeams()->save($team);
                $team->save();


                $user->teams()->attach($team);
                $user->save();
            }

            /*
             * Check if team already signed in event
             */
            $team = $user->ownTeams()->where('team_name', $user_name)->get();

            $exist = $event->teams->contains($team[0]->id);
            if ($exist == null) {
                $event->teams()->attach($team);
                $event->save();
                return response()->json('', 200);
            }
            return response()->json('Already exists', 200);

        }
        $team_id = $request->get('team_id');
        $team = Team::findOrFail($team_id);
        $exist = $event->teams->contains($team_id);
        if ($exist == null) {
            $event->teams()->attach($team);
            $event->save();
        }
        //TODO skontrolovat, ci toto funguje
        return response()->json('', 200);
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
