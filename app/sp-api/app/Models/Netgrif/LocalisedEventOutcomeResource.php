<?php
/**
 * LocalisedEventOutcomeResource
 */
namespace App\Models\Netgrif;

use DateTime;

/**
 * LocalisedEventOutcomeResource
 */
class LocalisedEventOutcomeResource {

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
