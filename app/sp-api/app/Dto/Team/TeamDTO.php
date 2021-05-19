<?php


namespace App\Dto\Team;


use App\Dto\User\UserDTO;
use App\Dto\Event\EventDTO;
use App\Models\AppSerializable;
use DateTime;

/** @typescript */
class TeamDTO extends AppSerializable
{
    public ?string $id;
    public ?string $team_name;
    public ?DateTime $created_at;
    public ?DateTime $updated_at;
    public ?DateTime $registration_start;
    public ?UserDTO $owner;
    public ?int $points;
    public ?int $wins;
    public ?int $events_total;
    public ?bool $disabled;
    /**
     * @var UserDTO[]|null
     */
    public ?array $users;

    /**
     * @var EventDTO[]|null
     */
    public ?array $active_events;
    /**
     * @var EventDTO[]|null
     */
    public ?array $ended_events;
    /**
     * @var EventDTO[]|null
     */
    public ?array $future_events;



}
