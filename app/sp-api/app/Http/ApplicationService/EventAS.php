<?php


namespace App\Http\ApplicationService;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

abstract class EventAS
{
    public static function showEvents(): JsonResponse
    {
        // todo - withBasicAuth bude extrahovane mimo a nova funkcia vrati iba `pendingRequest`
        $jsonResponse = Http::withBasicAuth(
            env('API_INTERES_USERNAME'),
            env('API_INTERES_AIS_ID')
        )->get(
            env('API_INTERES_URL') . "/task"
        )->json();

        return response()->json($jsonResponse);
    }
}
