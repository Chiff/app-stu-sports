<?php
/**
 * PetriNetReferenceResources
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * PetriNetReferenceResources
 */
class PetriNetReferenceResources extends AppSerializable
{

    /** @var PetriNetReferenceResource[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
