<?php
/**
 * TaskLayout
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TaskLayout
 */
class TaskLayout extends AppSerializable
{

    /** @var int $cols */
    public $cols;

    /** @var string $fieldAlignment */
    public $fieldAlignment;

    /** @var int $offset */
    public $offset;

    /** @var int $rows */
    public $rows;

    /** @var string $type */
    public $type;

}
