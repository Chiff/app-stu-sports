<?php
/**
 * LocalisedTaskResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;
use DateTime;

/**
 * LocalisedTaskResource
 */
class LocalisedTaskResource extends AppSerializable
{

    /** @var string $caseColor */
    public $caseColor;

    /** @var string $caseId */
    public $caseId;

    /** @var string $caseTitle */
    public $caseTitle;

    /** @var Field[] $immediateData */
    public $immediateData;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $transitionId */
    public $transitionId;

}
