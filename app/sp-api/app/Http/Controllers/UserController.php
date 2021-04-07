<?php


namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User\LoginCredentials;
use Illuminate\Http\JsonResponse;
use JsonMapper\JsonMapper;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\Cookie;

class UserController extends Controller
{
    private UserService $userService;
    private JsonMapper $mapper;

    public function __construct(UserService $userService, JsonMapper $mapper, array $attributes = [])
    {
        $this->userService = $userService;
        $this->mapper = $mapper;

        $this->middleware('auth', ['except' => ['login']]);
    }

    public function login(): JsonResponse
    {
        // TODO - 06/04/2021 - toto je skarede, vyriesim inokedy
        $credentials = new LoginCredentials();
        $credentials->email = request(['email'])["email"];
        $credentials->password = request(['password'])["password"];

        $user = $this->userService->login($credentials);

        if (!$user) {
            // todo as exception
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = auth()->login($user);

        if (!$token) {
            // todo as exception
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($user, $token);
    }

    public function logout(): JsonResponse
    {
        $token = auth()->tokenById(auth()->user()->id);
        auth()->invalidate(true);
        auth()->logout();

        $r = array(
            'error' => array(
                'code' => 200,
                'message' => 'Success',
            ));

        return $this->respondWithToken($r, $token, 0);
    }

    public function detail(): JsonResponse
    {
        $user = $this->userService->detail();
        return response()->json($user);
    }

    protected function respondWithToken($data, string $token, int $overrideTTLMinutes = -1): JsonResponse
    {
        $expSecond = auth()->factory()->getTTL() * 60;
        if ($overrideTTLMinutes != -1) {
            $expSecond = $overrideTTLMinutes * 60;
        }

        $cookie = new Cookie('token', $token, time() + $expSecond , "/", null, true, true,false,'Strict');

        return response()
            ->json($data)
            ->withCookie($cookie);
    }
}
