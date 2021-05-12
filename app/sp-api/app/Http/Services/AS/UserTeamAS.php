<?php


namespace App\Http\Services\AS;


use App\Dto\Team\TeamDTO;
use App\Dto\User\UserDTO;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;
use JsonMapper\JsonMapper;

class UserTeamAS
{
    private JsonMapper $mapper;

    public function __construct(
        JsonMapper $mapper,
    )
    {
        $this->mapper = $mapper;
    }


    /**
     * @param Collection $teams
     * @return TeamDTO[]
     */
    public function mapTeamsWithOwner(Collection $teams): array
    {
        $result = [];

        foreach ($teams as $team) {
            $teamDTO = $this->mapTeamWithOwner($team);
            array_push($result, $teamDTO);
        }

        return $result;

    }

    public function mapTeamWithOwner(Team $team): TeamDTO
    {
        $teamDTO = new TeamDTO();
        $this->mapper->mapObjectFromString($team->toJson(), $teamDTO);

        $userDto = new UserDTO();
        $userModel = User::whereId($team->user_id)->first()->toJson();
        $this->mapper->mapObjectFromString($userModel, $userDto);

        $teamDTO->owner = $userDto;
        return $teamDTO;
    }

    public function mapTeamDetail(Team $team): TeamDTO
    {
        $teamDto = $this->mapTeamWithOwner($team);

        // TODO - 12/05/2021 - @msteklac/@mrybar - pridaj uzivatelov
        // TODO - 12/05/2021 - @msteklac/@mrybar - pridaj akitvne eventy teamu
        // TODO - 12/05/2021 - @msteklac/@mrybar - pridaj skoncene eventy teamu
        // TODO - 12/05/2021 - @mfilo - pridaj statistiku? - vyhry, clenovia, sporty...

        return $teamDto;
    }


    /**
     * @param User $user
     * @return TeamDTO[]
     */
    public function appendTeamsToUser(User $user): array
    {
        $result = [];
        $teams = Team::all();

        foreach ($teams as $team) {
            if ($team->teamMembers()->where('user_id', '=', $user->id)) {
                $teamDTO = $this->mapTeamWithOwner($team);
                array_push($result, $teamDTO);
            }
        }

        return $result;
    }

}
