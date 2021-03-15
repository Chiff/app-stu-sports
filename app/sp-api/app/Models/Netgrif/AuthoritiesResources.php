<?php
/**
 * AuthoritiesResources
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * AuthoritiesResources
 */
class AuthoritiesResources extends AppSerializable
{

    /** @var Authority[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
