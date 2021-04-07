<?php


namespace App\Http\Services;

use App\Http\Services\Netgrif\AuthenticationService;
use App\Models\User;
use App\Models\User\LoginCredentials;
use Illuminate\Contracts\Auth\Authenticatable;

class UserService
{
    private AuthenticationService $netgrigfAuth;

    public function __construct(AuthenticationService $authService)
    {
        $this->netgrigfAuth = $authService;
    }

    public function login(LoginCredentials $credentials): User|null
    {
        $userResource = $this->netgrigfAuth->login($credentials);


        if ($userResource == null) {
            return null;
        }

        $user = User::where('ext_id', $userResource->id)->first();

        // prve prihlasenie
        if (!$user) {
            $user = new User;
            $user->email = $userResource->email;
            $user->firstname = $userResource->name;
            $user->surname = $userResource->surname;
            $user->ext_id = $userResource->id;

            $user = $user->save();
        }

        return $user;
    }

    public function detail(): Authenticatable|null // User|null
    {
        return auth()->user();
    }
}
