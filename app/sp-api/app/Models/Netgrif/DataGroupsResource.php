<?php
/**
 * DataGroupsResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * DataGroupsResource
 */
class DataGroupsResource extends AppSerializable
{

    /** @var DataGroup[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
