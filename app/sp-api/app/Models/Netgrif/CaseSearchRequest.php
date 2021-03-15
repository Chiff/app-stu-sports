<?php
/**
 * CaseSearchRequest
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * CaseSearchRequest
 */
class CaseSearchRequest extends AppSerializable
{

    /** @var Author $author */
    public $author;

    /** @var string $fullText */
    public $fullText;

    /** @var string $group */
    public $group;

    /** @var PetriNet $petriNet */
    public $petriNet;

    /** @var string $processIdentifier */
    public $processIdentifier;

    /** @var string $query */
    public $query;

    /** @var string $role */
    public $role;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $transition */
    public $transition;

}
