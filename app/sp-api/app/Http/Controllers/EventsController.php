<?php


namespace App\Http\Controllers;

use App\Http\Services\EventService;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class EventsController extends Controller
{
    private EventService $event;

    // tento kod zaruci ze auth middleware nebude obmedzovat showUserEvents + showOneEvent avsak musis byt prihlaseny na C,U,D
    public function __construct(EventService $event, array $attributes = [])
    {
        $this->event = $event;

        $this->middleware('auth', ['only' => [
            'create',
            'update',
            'delete'
        ]]);
    }

    public function showUserEvents(): JsonResponse
    {
        $res = $this->event->showEvents();
        return response()->json($res);
    }

    public function showOneEvent($id)
    {
        return response()->json(Event::find($id));
    }

    public function create(Request $request)
    {
        $event = Event::create($request->all());

        return response()->json($event, 201);
    }

    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return response()->json($event, 200);
    }

    public function delete($id)
    {
        Event::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }


}
