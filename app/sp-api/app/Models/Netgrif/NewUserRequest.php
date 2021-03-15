<?php
/**
 * NewUserRequest
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * NewUserRequest
 */
class NewUserRequest extends AppSerializable
{

    /** @var string $email */
    public $email;

    /** @var int[] $groups */
    public $groups;

    /** @var string[] $processRoles */
    public $processRoles;

}
