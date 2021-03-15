<?php
/**
 * ObjectId
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;
use DateTime;

/**
 * ObjectId
 */
class ObjectId extends AppSerializable
{

    /** @var int $counter */
    public $counter;

    /** @var DateTime $date */
    public $date;

    /** @var int $machineIdentifier */
    public $machineIdentifier;

    /** @var int $processIdentifier */
    public $processIdentifier;

    /** @var int $time */
    public $time;

    /** @var int $timeSecond */
    public $timeSecond;

    /** @var int $timestamp */
    public $timestamp;

}
