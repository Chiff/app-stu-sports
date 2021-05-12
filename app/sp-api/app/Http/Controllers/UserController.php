<?php


namespace App\Http\Controllers;

use App\Dto\Team\TeamDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\User\LoginCredentials;
use Illuminate\Http\JsonResponse;
use JsonMapper\JsonMapper;
use Laravel\Lumen\Routing\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use App\Models\Team;


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

        $this->userService->encodeCredentials($credentials);
        return $this->respondWithToken($user, $token);
    }

    public function logout(): JsonResponse
    {
        $token = auth()->tokenById(auth()->user()->getAuthIdentifier());
        auth()->invalidate(true);
        auth()->logout();

        $r = array(
            'error' => array(
                'code' => 200,
                'message' => 'Success',
            ));

        return $this->respondWithToken($r, $token, 0);
    }

    //TODO :: dorobit do UserDTO pole teamov a vypisat timy takymto stylom
    public function detail(): JsonResponse
    {
        $result = [];
        $user = $this->userService->detail();
        $user->toJson();
        $userDTO = new UserDTO();

        $this->mapper->mapObjectFromString($user, $userDTO);
        // TODO:: otazka na matusa, vraca to vsetky timy kde je user prihlaseny alebo len kde je owner?
        $teams = Team::whereUserId($user->id)->get();

        array_push($result, $userDTO);

        foreach ($teams as $team) {
            $teamDTO = new TeamDTO();
            $this->mapper->mapObjectFromString($team->toJson(), $teamDTO);
            $team_owner = $team->owner()->get();
            $team_owner->toJson();

            $it_1 = json_decode($user, TRUE);
            $it_2 = json_decode($team_owner, TRUE);
            $result_array = array_diff($it_1,$it_2);

            if(empty($result_array[0])){
                $teamDTO->owner = $userDTO;
            }

            else{
                $ownerDTO = new UserDTO();
                $this->mapper->mapObjectFromString($team_owner, $ownerDTO);
                $teamDTO->owner = $ownerDTO;
            }

            array_push($result, $teamDTO);
        }


        return response()->json($result);
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



    public function showAllUsers(): JsonResponse
    {
        $res = $this->userService->getAllUsers();
        return response()->json($res);
    }

}
