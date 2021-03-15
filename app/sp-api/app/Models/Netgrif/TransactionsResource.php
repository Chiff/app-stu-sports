<?php
/**
 * TransactionsResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TransactionsResource
 */
class TransactionsResource extends AppSerializable
{

    /** @var TransactionResource[] $content */
    public $content;

    /** @var Link $links */
    public $links;

}
