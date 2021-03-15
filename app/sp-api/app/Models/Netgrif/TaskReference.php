<?php
/**
 * TaskReference
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TaskReference
 */
class TaskReference extends AppSerializable
{

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $transitionId */
    public $transitionId;

}
