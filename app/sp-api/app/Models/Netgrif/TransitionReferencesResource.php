<?php
/**
 * TransitionReferencesResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TransitionReferencesResource
 */
class TransitionReferencesResource extends AppSerializable
{

    /** @var TransitionReference[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
