<?php


namespace App\Dto\User;


use App\Models\AppSerializable;
use DateTime;

/** @typescript */
class UserDTO extends AppSerializable
{
    public ?string $firstname;
    public ?string $surname;
    public ?string $email;
    public ?DateTime $created_at;
}
