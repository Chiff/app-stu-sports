<?php


namespace App\Http\Services\AS;

use App\Dto\Event\EventDTO;
use App\Dto\Team\TeamDTO;
use App\Dto\User\UserDTO;
use App\Http\Utils\DateUtil;
use App\Models\Event;
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

        $events = $team->getSignedEvents()->orderBy('event_end', 'desc')->get();
        $active = [];
        $finished = [];
        $future = [];

        $todayDate = DateUtil::now();
        foreach ($events as $event) {
            if (!$event instanceof Event) continue; // type fix

            $eventDto = new EventDTO();
            $this->mapper->mapObjectFromString($event->toJson(), $eventDto);

            $userDto = new UserDTO();
            $user = User::whereId($eventDto->user_id)->first();
            $this->mapper->mapObjectFromString($user->toJson(), $userDto);

            $eventDto->owner = $userDto;

            $hasWinner = $event->teams()->where('is_winner', '=', true)->count() > 0;
            $isFuture = !$eventDto->disabled && $todayDate < $eventDto->event_start;
            $isActive = !$eventDto->disabled && $todayDate >= $eventDto->event_start && !$hasWinner;
            $isEnded = $eventDto->disabled || ($todayDate >= $eventDto->event_end && $hasWinner);

            if ($isActive) {
                array_push($active, $eventDto);
            } elseif ($isEnded) {
                array_push($finished, $eventDto);
            } elseif ($isFuture) {
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
        $teams = $user->teams()->orderBy('disabled')->orderBy('created_at', 'desc')->get();
        return $this->mapTeamsWithOwner($teams);
    }

}
