<?php


namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function showUserEvents(): JsonResponse
    {
        return response()->json(Event::all());
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
