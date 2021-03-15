<?php
/**
 * LocalisedFilterResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;
use DateTime;

/**
 * LocalisedFilterResource
 */
class LocalisedFilterResource extends AppSerializable
{

    /** @var Author $author */
    public $author;

    /** @var DateTime $created */
    public $created;

    /** @var string $description */
    public $description;

    /** @var Link $links */
    public $links;

    /** @var string $mergeOperation */
    public $mergeOperation;

    /** @var string $query */
    public $query;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $type */
    public $type;

    /** @var int $visibility */
    public $visibility;

}
