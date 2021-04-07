<?php


namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;


class UserController extends Controller
{
    private UserService $user_service;

    public function __construct(UserService $user_service, array $attributes = [])
    {
        $this->user_service = $user_service;

        $this->middleware('auth', ['only' => [
            'create',
            'update',
            'delete'
        ]]);
    }

    public function showAllUsers(): JsonResponse
    {
        $res = $this->user_service->getAllUsers();
        return response()->json($res);
    }


}
