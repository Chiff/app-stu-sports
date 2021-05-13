<?php


namespace App\Dto\Event;


use App\Dto\CiselnikDTO;
use App\Dto\User\UserDTO;
use App\Models\AppSerializable;
use DateTime;

/** @typescript */
class EventDTO extends AppSerializable
{
    public static array $validationRules = [
        'name' => 'required|max:255',
        'min_teams' => 'required|integer|min:1',
        'max_teams' => 'required|integer|min:1',
        'min_team_members' => 'required|integer|min:1',
        'max_team_members' => 'required|integer|min:1',
        'registration_start' => 'required|date|date_format:Y-m-d\TH:i:s',
        'registration_end' => 'required|date|date_format:Y-m-d\TH:i:s',
        'event_start' => 'required|date|date_format:Y-m-d\TH:i:s',
        'event_end' => 'required|date|date_format:Y-m-d\TH:i:s',
        'description' => 'max:4096',
        'type' => 'required',
        // autoaticky vyplnene
        // 'id' => '',
        // 'owner' => '',
        // 'created_at' => '',
        // 'updated_at' => '',
        // 'user_id' => '',
    ];

    public ?string $id = null;
    public ?string $user_id = null;
    public ?string $name = null;
    public ?int $min_teams = null;
    public ?int $max_teams = null;
    public ?int $min_team_members = null;
    public ?int $max_team_members = null;
    public ?DateTime $created_at = null;
    public ?DateTime $updated_at = null;
    public ?DateTime $registration_start = null;
    public ?DateTime $registration_end = null;
    public ?DateTime $event_start = null;
    public ?DateTime $event_end = null;
    public ?UserDTO $owner = null;
    public ?string $description = null;
    public ?CiselnikDTO $type = null;
}
