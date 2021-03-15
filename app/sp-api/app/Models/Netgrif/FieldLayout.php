<?php
/**
 * FieldLayout
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * FieldLayout
 */
class FieldLayout extends AppSerializable
{

    /** @var string $alignment */
    public $alignment;

    /** @var string $appearance */
    public $appearance;

    /** @var int $cols */
    public $cols;

    /** @var int $offset */
    public $offset;

    /** @var int $rows */
    public $rows;

    /** @var string $template */
    public $template;

    /** @var int $x */
    public $x;

    /** @var int $y */
    public $y;

}
