<?php


namespace App\Http\Controllers;

use App\Http\Services\TeamService;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TeamController extends Controller
{
    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->middleware('auth');
        $this->teamService = $teamService;
    }


    /*
     * Create a team
     */
    public function createTeam(Request $request): JsonResponse
    {
        $this->validate($request, [
            'team_name' => 'required'
        ]);
        $team = null;
        $user_id = auth()->id();
        $user = User::findorfail($user_id);

        $team_name = $request->get('team_name');

        $exist = $user->ownTeams()->where('team_name', $team_name)->get();
        if (count($exist) < 1) {
            $team = new Team(array('team_name' => $team_name));
            $user->ownTeams()->save($team);
            $team->team_members()->save($user);
            return response()->json($team, 200);
        }

        throw new Exception("Tím s rovnakým menom už existuje", 304);
    }

    public function getTeamById(int $id): JsonResponse
    {
        $team = $this->teamService->getTeamById($id);
        return response()->json($team, 200);
    }


    public function updateTeam($team_id, Request $request): JsonResponse
    {
        $team = Event::findOrFail($team_id);
        $team->update($request->all());
        return response()->json($team, 200);
    }

    public function showAllUserTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllteamsWhereIsUser();
        return response()->json($teams);
    }


    // TODO:: zatial nefunkcne
    public function showAllTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllTeams();
        return response()->json($teams, 200);
    }

    public function addOneMemberToTeamByEmail(int $id, Request $request)
    {
        $team = Team::whereId($id)->first();
        if (!$team) {
            throw new \Exception("Tím nebol nájdený");
        }

        $loggedUserId = auth()->id();
        if ($team->user_id != $loggedUserId) {
            throw new \Exception("Nie ste vlastníkom tímu");
        }

        $this->validate($request, ['user_mail' => 'required|email']);
        $userEmail = $request->get('user_mail');

        $user = User::whereEmail($userEmail)->first();
        if (!$user) {
            throw new \Exception("Užívateľ nebol nájdený");
        }

        $hasEvent = $user->teams()->get()->contains('id', '=', $team->id);
        if ($hasEvent) {
            throw new \Exception("Užívateľ už sa nachádza v tomto tíme");
        }

        $user->teams()->save($team);
        return response()->json();
    }


    public function deleteTeamById(int $team_id): JsonResponse
    {
        $user_id = auth()->id();
        $team = Team::whereId($team_id)->first();

        if (!$team) {
            throw new \Exception("Takýto tím neexistuje");
        }

        if ($team->user_id == $user_id){
            $team->disabled = 1;
            $team->save();
            return response()->json('Tím vymazaný', 200);
        }

        return response()->json('"Nie ste vlastníkom tímu', 301);
    }

}
