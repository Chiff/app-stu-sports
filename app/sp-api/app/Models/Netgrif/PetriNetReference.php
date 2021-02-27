<?php
/**
 * PetriNetReference
 */
namespace App\Models\Netgrif;

use DateTime;

/**
 * PetriNetReference
 */
class PetriNetReference {

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

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $version */
    public $version;

}
