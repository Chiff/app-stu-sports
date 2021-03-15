<?php
/**
 * DataFieldsResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * DataFieldsResource
 */
class DataFieldsResource extends AppSerializable
{

    /** @var LocalisedField[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
