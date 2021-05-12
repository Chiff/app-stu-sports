<?php


namespace App\Http\Services;

use App\Dto\Team\TeamDTO;
use App\Dto\User\UserDTO;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;
use function MongoDB\BSON\toJSON;

//TODO :: spravit vypisanie team memberov z timu - v Team classe je na to metoda, len neviem ci nemame napicu db model
// napicu db model pojebava aj nieco na styl $teams = Team::whereUserId($user->id)->get();, kedze v db uchovavame len
// ownera a nie ludi v time...
class TeamService
{
    private AuthenticationService $auth;
    private JsonMapper $mapper;
    private WorkflowService $workflowService;
    private UserService $userService;

    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        UserService $userService,
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->userService = $userService;
    }

    public function getAllTeams(): array
    {
        $teams = Team::all();

        return $this->mapTeamsWithOwner($teams);
    }


    // TODO: nie je spravene posielanie na netgrif
    public function getOwnTeams(): array
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $teams = $user->ownTeams()->get();

        return $this->mapTeamsWithOwner($teams);
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


    /**
     * @param Collection $teams
     * @return TeamDTO[]
     */
    private function mapTeamsWithOwner(Collection $teams): array
    {
        $result = [];

        foreach ($teams as $team) {

            $teamDTO = new TeamDTO();
            $this->mapper->mapObjectFromString($team->toJson(), $teamDTO);

            $user = new UserDTO();
            $userModel = User::whereId($team->user_id)->first()->toJson();
            $this->mapper->mapObjectFromString($userModel, $user);

//            $eDto = new EventDTO();
//            foreach ($event as $keyname=>$val){
//              $eDto->{$keyname}=$val;
//            }

            $teamDTO->owner = $user;

            //  dd($eventDTO);

            array_push($result, $teamDTO);
        }

        return $result;

    }
}
