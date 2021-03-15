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

    /** @var string $assignPolicy */
    public $assignPolicy;

    /** @var string $assignTitle */
    public $assignTitle;

    /** @var string $cancelTitle */
    public $cancelTitle;

    /** @var string $caseColor */
    public $caseColor;

    /** @var string $caseId */
    public $caseId;

    /** @var string $caseTitle */
    public $caseTitle;

    /** @var string $dataFocusPolicy */
    public $dataFocusPolicy;

    /** @var string $delegateTitle */
    public $delegateTitle;

    /** @var DateTime $finishDate */
    public $finishDate;

    /** @var string $finishPolicy */
    public $finishPolicy;

    /** @var string $finishTitle */
    public $finishTitle;

    /** @var int $finishedBy */
    public $finishedBy;

    /** @var string $icon */
    public $icon;

    /** @var Field $immediateData */
    public $immediateData;

    /** @var TaskLayout $layout */
    public $layout;

    /** @var Link $links */
    public $links;

    /** @var int $priority */
    public $priority;

    /** @var bool $requiredFilled */
    public $requiredFilled;

    /** @var DateTime $startDate */
    public $startDate;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $transactionId */
    public $transactionId;

    /** @var string $transitionId */
    public $transitionId;

    /** @var User $user */
    public $user;

}
