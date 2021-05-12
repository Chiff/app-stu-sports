<?php


namespace App\Http\Services;

use App\Dto\Team\TeamDTO;
use App\Exceptions\RequestErrorException;
use App\Http\Services\AS\UserTeamAS;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Team;
use App\Models\User;
use JsonMapper\JsonMapper;

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



    public function getTeamById(int $id): TeamDTO
    {
        $team = Team::whereId($id)->first();

        if (!$team) {
            throw new RequestErrorException("not found");
        }

        //TODO :: spravit vypisanie team memberov z timu - v Team classe je na to metoda, len neviem ci nemame napicu db model
        // napicu db model pojebava aj nieco na styl $teams = Team::whereUserId($user->id)->get();, kedze v db uchovavame len
        // ownera a nie ludi v time...
        return $this->userTeamAS->mapTeamDetail($team);
    }

    public function getAllteamsWhereIsUser(): array
    {
        $user_id = auth()->id();
        $user = User::whereId($user_id)->first();

        return $this->userTeamAS->getTeamsByUser($user);
    }

}
