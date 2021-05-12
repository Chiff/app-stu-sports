<?php


namespace App\Http\Services;

use App\Http\Services\AS\UserTeamAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Team;
use App\Models\User;
use JsonMapper\JsonMapper;

//TODO :: spravit vypisanie team memberov z timu - v Team classe je na to metoda, len neviem ci nemame napicu db model
// napicu db model pojebava aj nieco na styl $teams = Team::whereUserId($user->id)->get();, kedze v db uchovavame len
// ownera a nie ludi v time...
class TeamService
{
    private AuthenticationService $auth;
    private JsonMapper $mapper;
    private WorkflowService $workflowService;
    private UserTeamAS $userTeamAS;

    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserTeamAS $userTeamAS,
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userTeamAS = $userTeamAS;
    }

    public function getAllTeams(): array
    {
        $teams = Team::all();
        return $this->userTeamAS->mapTeamsWithOwner($teams);
    }


    // TODO: nie je spravene posielanie na netgrif
    public function getOwnTeams(): array
    {
        $user_id = auth()->id();
        $user = User::whereId($user_id);
        $teams = $user->ownTeams()->get();

        return $this->userTeamAS->mapTeamsWithOwner($teams);
    }

    // TODO:: otazka na matusa, vraca to vsetky timy kde je user prihlaseny alebo len kde je owner?
    // TODO:: DTO spravit..
    // TODO:: zatial nefunkcne
//    public function getAllteamsWhereIsUser(): array
//    {
//        $result = [];
//
//        $user_id = auth()->id();
//        $user = User::findOrFail($user_id);
//        $teams = Team::whereUserId($user->id)->get();
//
//        return $teams;
//    }

}
