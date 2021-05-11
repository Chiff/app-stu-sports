<?php


namespace App\Http\Controllers;

use App\Http\Services\SystemService;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TeamController extends Controller
{
    private SystemService $systemService;

    public function __construct(SystemService $systemService)
    {

        $this->middleware('auth');
        $this->systemService = $systemService;
    }


    /*
     * Create a team
     */
    public function createTeam(Request $request): JsonResponse
    {
        $this->validate($request, [
            'teamName' => 'required'
        ]);
        $team = null;
        $user_id = auth()->id();
        $user = User::findorfail($user_id);

        $team_name = $request->get('teamName');

        $exist = $user->ownTeams()->where('team_name', $team_name)->get();
        if (count($exist) < 1) {
            $team = new Team(array('team_name' => $team_name));
            $user->ownTeams()->save($team);
            return response()->json($team, 200);
        }

            return response()->json('Not created, team with this name already exists', 304);
    }

    /*
     * Update team
     */
    public function updateTeam($team_id, Request $request): JsonResponse
    {
        $team = Event::findOrFail($team_id);
        $team->update($request->all());
        return response()->json($team, 200);
    }

}
