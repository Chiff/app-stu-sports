<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class Authenticate
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next, string|null $guard = null): mixed
    {

        $rawToken = $request->cookie('token');
        if ($rawToken) {
            $token = new Token($rawToken);
            $payload = JWTAuth::decode($token);

            $userId = $payload['sub'];
            $u = User::find($userId);
            auth()->login($u);
        }


        if ($this->auth->guard($guard)->guest()) {
            $r = array(
                'error' => array(
                    'code' => 401,
                    'message' => 'Unauthorized',
                ));

            return response()->json($r, 401);
        }

        return $next($request);
    }
}
