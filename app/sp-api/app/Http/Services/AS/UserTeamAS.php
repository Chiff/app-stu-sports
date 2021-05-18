<?php


namespace App\Http\Services\AS;

use App\Models\Event;
use App\Models\EventTeam;
use Faker\Provider\DateTime;
use App\Dto\Event\EventDTO;
use App\Dto\Team\TeamDTO;
use App\Dto\User\UserDTO;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;
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
            $teamDTO = $this->mapTeamDetail($team);
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
        $members = $team->team_members()->get();

        $team_array = [];

        foreach ($members as $member) {
            $userDto = new UserDTO();
            $this->mapper->mapObjectFromString($member->toJson(), $userDto);
            array_push($team_array, $userDto);
        }
        $teamDto->users = $team_array;

        $events = $team->getSignedEvents()->get();
        $active = [];
        $finished = [];
        $future = [];

        $todayDatee = date('Y-m-dTH:m:i');


        foreach ($events as $event) {
            $eventDto = new EventDTO();
            $userDto = new UserDTO();

            $dt = new \DateTime($todayDatee);
            $todayDate = Carbon::instance($dt);

            $this->mapper->mapObjectFromString($event->toJson(), $eventDto);

            $user = User::whereId($eventDto->user_id)->first();
            $this->mapper->mapObjectFromString($user->toJson(), $userDto);

            $eventDto->owner = $userDto;

            if (($todayDate < $eventDto->event_end) && ($todayDate > $eventDto->event_start)){
                array_push($active, $eventDto);
            }
            elseif ($todayDate > $eventDto->event_end){
                array_push($finished, $eventDto);
            }
            elseif($todayDate < $eventDto->event_start){
                array_push($future, $eventDto);
            }
        }

        $teamDto->active_events = $active;
        $teamDto->ended_events = $finished;
        $teamDto->future_events = $future;

        // TODO - 12/05/2021 - @mfilo - pridaj statistiku? - vyhry, clenovia, sporty...

        return $teamDto;
    }


    /**
     * @param User $user
     * @return TeamDTO[]
     */
    public function getTeamsByUser(User $user): array
    {
        $teams = $user->teams()->get();
        return $this->mapTeamsWithOwner($teams);
    }

}
