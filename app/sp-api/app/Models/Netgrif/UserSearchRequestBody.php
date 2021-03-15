<?php
/**
 * UserSearchRequestBody
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * UserSearchRequestBody
 */
class UserSearchRequestBody extends AppSerializable
{

    /** @var string $fulltext */
    public $fulltext;

    /** @var string $roles */
    public $roles;

}
