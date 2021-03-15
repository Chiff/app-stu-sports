<?php
/**
 * Preferences
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * Preferences
 */
class Preferences extends AppSerializable
{

    /** @var string $locale */
    public $locale;

    /** @var int $userId */
    public $userId;

}
