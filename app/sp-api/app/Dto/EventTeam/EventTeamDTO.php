<?php


namespace App\Dto\EventTeam;



use App\Models\AppSerializable;


/** @typescript */
class EventTeamDTO extends AppSerializable
{
    public ?string $id_team;
    public ?string $id_event;
    public ?int $points;
    public ?bool $is_winner;
}
