<?php
/**
 * TransactionResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TransactionResource
 */
class TransactionResource extends AppSerializable
{

    /** @var Link $links */
    public $links;

    /** @var string $title */
    public $title;

    /** @var string $transitions */
    public $transitions;

}
