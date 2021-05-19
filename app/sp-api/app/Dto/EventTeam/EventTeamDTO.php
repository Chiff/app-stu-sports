<?php


namespace App\Dto\EventTeam;



use App\Models\AppSerializable;


/** @typescript */
class EventTeamDTO extends AppSerializable
{
    public ?string $team_id;
    public ?string $team_name;
    public ?string $event_id;
    public ?int $points;
    public ?bool $is_winner;
}
