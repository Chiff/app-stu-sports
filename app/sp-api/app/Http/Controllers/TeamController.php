<?php


namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TeamController extends Controller
{
    public function __construct(array $attributes = [])
    {
        $this->middleware('auth');
    }

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
        }

        return response()->json($team);
    }

}
