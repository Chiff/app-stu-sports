<?php
/**
 * RegistrationRequest
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * RegistrationRequest
 */
class RegistrationRequest extends AppSerializable
{

    /** @var string $name */
    public $name;

    /** @var string $password */
    public $password;

    /** @var string $surname */
    public $surname;

    /** @var string $token */
    public $token;

}
