<?php
/**
 * PetriNetReferenceResource
 */
namespace App\Models\Netgrif;

use DateTime;

/**
 * PetriNetReferenceResource
 */
class PetriNetReferenceResource {

    /** @var Author $author */
    public $author;

    /** @var DateTime $createdDate */
    public $createdDate;

    /** @var string $defaultCaseName */
    public $defaultCaseName;

    /** @var string $icon */
    public $icon;

    /** @var string $identifier */
    public $identifier;

    /** @var DataFieldReference $immediateData */
    public $immediateData;

    /** @var string $initials */
    public $initials;

    /** @var Link $links */
    public $links;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $version */
    public $version;

}
