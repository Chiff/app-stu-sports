<?php
/**
 * ProcessRolesResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * ProcessRolesResource
 */
class ProcessRolesResource extends AppSerializable
{

    /** @var ProcessRoleResource[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
