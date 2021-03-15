<?php
/**
 * User
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * User
 */
class User extends AppSerializable
{

    /** @var Authority[] $authorities */
    public $authorities;

    /** @var string $avatar */
    public $avatar;

    /** @var string $email */
    public $email;

    /** @var string $fullName */
    public $fullName;

    /** @var Group[] $groups */
    public $groups;

    /** @var int $id */
    public $id;

    /** @var string $name */
    public $name;

    /** @var string[] $nextGroups */
    public $nextGroups;

    /** @var ProcessRole[] $processRoles */
    public $processRoles;

    /** @var string $surname */
    public $surname;

    /** @var string $telNumber */
    public $telNumber;

}
