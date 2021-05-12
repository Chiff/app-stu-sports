<?php


namespace App\Dto\User;


use App\Dto\Team\TeamDTO;
use App\Models\AppSerializable;
use DateTime;

/** @typescript */
class UserDTO extends AppSerializable
{
    public ?string $id;
    public ?string $firstname;
    public ?string $surname;
    public ?string $email;
    public ?DateTime $created_at;

    /**
     * @var TeamDTO[]|null
     */
    public ?array $teams;
}
