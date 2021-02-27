<?php


namespace App\Http\Services;

use App\Http\Services\Netgrif\AuthenticationService;
use App\Models\Netgrif\UserResource;

class EventService
{
    private AuthenticationService $auth;

    public function __construct(AuthenticationService $authService)
    {
        $this->auth = $authService;
    }

    public function showEvents(): UserResource
    {
        return $this->auth->login();
    }
}
