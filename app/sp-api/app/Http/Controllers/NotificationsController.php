<?php


namespace App\Http\Controllers;


use App\Http\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class NotificationsController extends Controller
{
    private NotificationService $notifService;

    public function __construct(NotificationService $notifService)
    {
        $this->middleware('auth');
        $this->notifService = $notifService;
    }

    public function getNotifications(string $entity_type, int $entity_id):JsonResponse
    {
        $notifications = $this->notifService->getNotifications($entity_type, $entity_id);
        return response()->json($notifications, 200);
    }

}
