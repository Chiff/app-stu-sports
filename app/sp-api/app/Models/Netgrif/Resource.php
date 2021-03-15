<?php
/**
 * Resource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * Resource
 */
class Resource extends AppSerializable
{

    /** @var string $description */
    public $description;

    /** @var string $filename */
    public $filename;

    /** @var bool $open */
    public $open;

    /** @var bool $readable */
    public $readable;

}
