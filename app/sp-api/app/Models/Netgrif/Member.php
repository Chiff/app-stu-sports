<?php
/**
 * Member
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * Member
 */
class Member extends AppSerializable
{

    /** @var string $email */
    public $email;

    /** @var Group[] $groups */
    public $groups;

    /** @var int $id */
    public $id;

    /** @var string $name */
    public $name;

    /** @var string $surname */
    public $surname;

    /** @var int $userId */
    public $userId;

}
