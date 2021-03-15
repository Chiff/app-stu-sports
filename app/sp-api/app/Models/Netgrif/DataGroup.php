<?php
/**
 * DataGroup
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * DataGroup
 */
class DataGroup extends AppSerializable
{

    /** @var string $alignment */
    public $alignment;

    /** @var DataFieldsResource $fields */
    public $fields;

    /** @var DataGroupLayout $layout */
    public $layout;

    /** @var bool $stretch */
    public $stretch;

    /** @var string $title */
    public $title;

}
