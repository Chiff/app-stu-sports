<?php


namespace App\Dto\Team;


use App\Dto\User\UserDTO;
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

    /**
     * @var UserDTO[]|null
     */
    public ?array $users;

}
