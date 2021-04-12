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

    // tento kod zaruci ze auth middleware nebude obmedzovat showUserEvents + showOneEvent avsak musis byt prihlaseny na C,U,D
    public function __construct(EventService $event, array $attributes = [])
    {
        $this->eventService = $event;

        $this->middleware('auth', ['except' => ['createOneEvent', 'showOneEvent', 'create']]);
    }

    public function showUserEvents(): JsonResponse
    {
        $owner_id = auth()->id();
        $events = Event::where('owner_id', $owner_id)->get();

        return response()->json($events);
    }

    public function showOneEventById($id): JsonResponse
    {
        $res = $this->eventService->showOneEvent($id);
        return response()->json($res);
    }

    public function showOneEventsByEventName($event_name): JsonResponse
    {
        $events = Event::where('owner_id', $event_name)->get();
        return response()->json($events);
    }

    public function deleteOneEvent($id)
    {
        //TODO kontrolovat, ci je aj vlastnikom eventu?
        Event::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function createOneEvent(Request $request)
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

    public function addOneParticipantToEventById(Request $request)
    {
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

    public function addOneParticipantToEventByEmail(Request $request)
    {
        //TODO skontrolovat
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


    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event, 200);
    }

}
