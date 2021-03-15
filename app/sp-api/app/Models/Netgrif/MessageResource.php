<?php
/**
 * MessageResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * MessageResource
 */
class MessageResource extends AppSerializable
{

    /** @var string $data */
    public $data;

    /** @var string $error */
    public $error;

    /** @var Link $links */
    public $links;

    /** @var string $success */
    public $success;

}
