<?php
/**
 * UpdateUserRequest
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * UpdateUserRequest
 */
class UpdateUserRequest extends AppSerializable
{

    /** @var string $avatar */
    public $avatar;

    /** @var string $name */
    public $name;

    /** @var string $surname */
    public $surname;

    /** @var string $telNumber */
    public $telNumber;

}
