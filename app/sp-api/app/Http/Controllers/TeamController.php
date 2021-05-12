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
            return response()->json($team, 200);
        }

        throw new Exception("Not created, team with this name already exists", 304);
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

    public function showUserTeams(): JsonResponse
    {
        $teams = $this->teamService->getOwnTeams();
        return response()->json($teams, 200);
    }

    public function showAllUserTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllteamsWhereIsUser();
        return response()->json($teams, 200);
    }


    // TODO:: zatial nefunkcne
    public function showAllTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllTeams();
        return response()->json($teams, 200);
    }


    //TODO :: asi problem so vztahmi, dorobit... nefunguje
    // "message": "Method Illuminate\\Database\\Eloquent\\Collection::teamMembers does not exist."
    public function addOneMemberToTeamByEmail(Request $request)
    {
        $this->validate($request, [
            'team_name' => 'required',
            'user_mail' => 'required|email'
        ]);

        $team_name = $request->get('team_name');
        $usermail = $request->get('user_mail');

        $user = User::where('email', $usermail)->get();

        $team = Team::where('team_name', $team_name)->get();

        $team->teamMembers()->get->attach($user);
        $team->save();


        return response()->json('', 200);
    }


}
