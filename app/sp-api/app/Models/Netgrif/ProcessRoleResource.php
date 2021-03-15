<?php
/**
 * ProcessRoleResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * ProcessRoleResource
 */
class ProcessRoleResource extends AppSerializable
{

    /** @var string $description */
    public $description;

    /** @var Link $links */
    public $links;

    /** @var string $name */
    public $name;

    /** @var string $stringId */
    public $stringId;

}
