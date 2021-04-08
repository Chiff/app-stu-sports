<?php


namespace App\Http\Controllers;

use App\Http\Services\EventService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class EventsController extends Controller
{
    //TODO pridat do autora id eventov, ktore vytvoril a na ktore je prihlaseny
    //TODO pridat do eventov id autorov a userov, ktori su prihlaseni na podujatie
    private EventService $eventService;

    // tento kod zaruci ze auth middleware nebude obmedzovat showUserEvents + showOneEvent avsak musis byt prihlaseny na C,U,D
    public function __construct(EventService $event, array $attributes = [])
    {
        $this->eventService = $event;

        $this->middleware('auth', ['only' => [
            'create',
            'update',
            'delete'
        ]]);
    }

    public function showUserEvents(): JsonResponse
    {
        $res = $this->eventService->showUserEvents();
        return response()->json($res);
    }

    public function showOneEvent($id): JsonResponse
    {
        $res = $this->eventService->showOneEvent($id);
        return response()->json($res);
    }

    public function createOneEvent(): CaseResource
    {
        $res = $this->eventService->createOneEvent();
        return $res;
    }

    public function deleteOneEvent(): JsonResponse
    {
        $res = $this->eventService->createOneEvent();
        return response()->json($res);
    }

    public function create(Request $request)
    {

        $netgrifEvent = $this->createOneEvent();

        $id = $netgrifEvent->stringId;
        $event = Event::create(array('id' => $id, 'name' => $request->get('name'), 'registration_start' => $request->get('registration_start'), 'registration_end' => $request->get('registration_end'), 'event_start' => $request->get('event_start'), 'max_participants' => $request->get('max_participants')));
        return response()->json($event, 200);
    }

    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return response()->json($event, 200);
    }

    public function delete($id)
    {
        //TODO spytat sa martina, preco nie je delete metoda povolena
        Event::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }


}
