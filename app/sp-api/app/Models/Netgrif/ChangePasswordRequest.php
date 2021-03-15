<?php
/**
 * ChangePasswordRequest
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * ChangePasswordRequest
 */
class ChangePasswordRequest extends AppSerializable
{

    /** @var string $login */
    public $login;

    /** @var string $newPassword */
    public $newPassword;

    /** @var string $password */
    public $password;

}
