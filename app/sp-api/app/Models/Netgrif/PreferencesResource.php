<?php
/**
 * PreferencesResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * PreferencesResource
 */
class PreferencesResource extends AppSerializable
{

    /** @var Link $links */
    public $links;

    /** @var string $locale */
    public $locale;

    /** @var int $userId */
    public $userId;

}
