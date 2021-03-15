<?php
/**
 * DataFieldReferencesResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * DataFieldReferencesResource
 */
class DataFieldReferencesResource extends AppSerializable
{

    /** @var DataFieldReference[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
