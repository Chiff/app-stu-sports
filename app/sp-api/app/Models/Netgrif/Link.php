<?php
/**
 * Link
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * Link
 */
class Link extends AppSerializable
{

    /** @var string $deprecation */
    public $deprecation;

    /** @var string $href */
    public $href;

    /** @var string $hreflang */
    public $hreflang;

    /** @var string $media */
    public $media;

    /** @var string $rel */
    public $rel;

    /** @var bool $templated */
    public $templated;

    /** @var string $title */
    public $title;

    /** @var string $type */
    public $type;

}
