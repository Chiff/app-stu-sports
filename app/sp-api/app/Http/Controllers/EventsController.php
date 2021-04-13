<?php


namespace App\Http\Controllers;

use App\Http\Services\EventService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use App\Models\User;
use Brick\Math\BigInteger;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use function PHPUnit\Framework\isEmpty;
use function Sodium\add;

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
        $todayDate = date('Y/m/d H:m:i');
        $events = Event::where('registration_start', '>=', $todayDate)->get();

        return response()->json($events);
    }

    /**
     * Get all events of logged user
     */
    public function showUserEvents(): JsonResponse
    {
        $owner_id = auth()->id();
        $events = Event::where('owner_id', $owner_id)->get();

        return response()->json($events);
    }

    /**
     * Find event by event id
     */
    public function showOneEventById($id): JsonResponse
    {
        $event = Event::findOrFail($id)->get();
        return response()->json($event);
    }

    /**
     * Find event by event name
     */
    public function showOneEventsByEventName($event_name): JsonResponse
    {
        $events = Event::where('name', $event_name)->get();
        return response()->json($events);
    }

    /**
     * Delete one event, by event id
     */
    public function deleteOneEvent($id): JsonResponse
    {
        //TODO kontrolovat, ci je aj vlastnikom eventu?
        Event::findOrFail($id)->delete();
        return response()->json(200);
    }

    /**
     * Create one event
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
            'max_participants' => 'required'
        ]);

        $event = new Event(array('owner_id' => $ownerId, 'ext_id' => $ext_id, 'name' => $request->get('eventName'), 'registration_start' => $request->get('registration_start'), 'registration_end' => $request->get('registration_end'), 'event_start' => $request->get('event_start'), 'max_participants' => $request->get('max_participants')));
        $event->save();
        return response()->json($event, 200);
    }

    /**
     * Add one participant to event, by participants id
     */
    public function addOneParticipantToEventById(Request $request)
    {
        //TODO spytat sa Mata, ci staci vraciat nejake string hlasky o uspesnosti alebo robit nejake custom json response
        $this->validate($request, [
            'event_id' => 'required',
            'user_id' => 'required'
        ]);

        $event_id = $request->get('event_id');
        $user_id = $request->get('user_id');

        $event = Event::findOrFail($event_id);
        $user = User::findOrFail($user_id);

        $exist = $event->participants->contains(auth()->id());
        if ($exist == null) {
            $event->participants()->attach($user_id);
            $event->save();
        }

    }

    /**
     * Add one participant to event, by participants email
     */
    public function addOneParticipantToEventByEmail(Request $request)
    {
        //TODO spytat sa Mata, ci staci vraciat nejake string hlasky o uspesnosti alebo robit nejake custom json response
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

    }

    /**
     * Update event info
     */
    public function update($id, Request $request): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event, 200);
    }

}
