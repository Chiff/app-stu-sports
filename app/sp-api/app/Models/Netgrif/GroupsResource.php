<?php
/**
 * GroupsResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * GroupsResource
 */
class GroupsResource extends AppSerializable
{

    /** @var Group[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
