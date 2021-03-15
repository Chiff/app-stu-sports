<?php
/**
 * CreateFilterBody
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * CreateFilterBody
 */
class CreateFilterBody extends AppSerializable
{

    /** @var string $description */
    public $description;

    /** @var string $query */
    public $query;

    /** @var string $title */
    public $title;

    /** @var string $type */
    public $type;

    /** @var int $visibility */
    public $visibility;

}
