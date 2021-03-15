<?php
/**
 * LocalisedEventOutcomeResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;
use DateTime;

/**
 * LocalisedEventOutcomeResource
 */
class LocalisedEventOutcomeResource extends AppSerializable
{

    /** @var User $assignee */
    public $assignee;

    /** @var string $error */
    public $error;

    /** @var DateTime $finishDate */
    public $finishDate;

    /** @var Link $links */
    public $links;

    /** @var DateTime $startDate */
    public $startDate;

    /** @var string $success */
    public $success;

}
