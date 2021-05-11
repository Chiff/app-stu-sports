<?php


namespace App\Dto\Event;


use App\Dto\User\UserDTO;
use App\Models\AppSerializable;
use DateTime;

/** @typescript */
class EventDTO extends AppSerializable
{

    public ?string $id;
    public ?string $user_id;
    public ?string $name;
    public ?int $min_teams;
    public ?int $max_teams;
    public ?int $min_team_members;
    public ?int $max_team_member;
    public ?DateTime $created_at;
    public ?DateTime $updated_at;
    public ?DateTime $registration_start;
    public ?DateTime $registration_end;
    public ?DateTime $event_start;
    public ?DateTime $event_end;
    public ?UserDTO $owner;
}
