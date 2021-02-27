<?php
/**
 * CaseResource
 */
namespace App\Models\Netgrif;

use DateTime;

/**
 * CaseResource
 */
class CaseResource {

    /** @var ObjectId $id */
    public $id;

    /** @var Author $author */
    public $author;

    /** @var string $color */
    public $color;

    /** @var DateTime $creationDate */
    public $creationDate;

    /** @var string $icon */
    public $icon;

    /** @var Field $immediateData */
    public $immediateData;

    /** @var DateTime $lastModified */
    public $lastModified;

    /** @var Link $links */
    public $links;

    /** @var string $petriNetId */
    public $petriNetId;

    /** @var ObjectId $petriNetObjectId */
    public $petriNetObjectId;

    /** @var string $processIdentifier */
    public $processIdentifier;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $visualId */
    public $visualId;

}
