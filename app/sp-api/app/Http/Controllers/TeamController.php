<?php


namespace App\Http\Controllers;

use App\Http\Services\TeamService;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Exception;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Model;
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


    //TODO :: done
    public function addOneMemberToTeamByEmail(Request $request)
    {
        $this->validate($request, [
            'team_name' => 'required',
            'user_mail' => 'required|email'
        ]);

        $team_name = $request->get('team_name');
        $usermail = $request->get('user_mail');

        $all_teams = Team::all();
        $all_users = User::all();

        $team_in_db = false;
        $user_in_db = false;


        foreach ($all_teams as $t) {
            $t->toJson();

            if ($team_name == $t->team_name){
                $team_in_db = true;
            }

        }

        foreach ($all_users as $u) {
            $u->toJson();

            if ($usermail == $u->email){
                $user_in_db = true;
            }

        }

        if (!$team_in_db){
            echo "err";
            return response()->json('Team sa nenachádza v DB', 200);
        }

        if (!$user_in_db){
            echo "err";
            return response()->json('User sa nenachádza v DB', 200);
        }

        $user = User::where('email', $usermail)->first();
        $team_vstup = Team::where('team_name', $team_name)->first();

        $already_in_team = false;
        $teams = $user->teams()->get();

        foreach ($teams as $team) {
            $team->toJson();
            $team_vstup->toJson();

            if($team_vstup->id == $team->id){
                $already_in_team = true;
            }

        }

        if (!$already_in_team){
            $user->teams()->save($team_vstup);
        }

        return response()->json('', 200);
    }


}
