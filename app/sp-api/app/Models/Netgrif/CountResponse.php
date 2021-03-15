<?php
/**
 * CountResponse
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * CountResponse
 */
class CountResponse extends AppSerializable
{

    /** @var int $count */
    public $count;

    /** @var string $entity */
    public $entity;

}
