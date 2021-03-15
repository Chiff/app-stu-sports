<?php
/**
 * PetriNetReferenceWithMessageResource
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * PetriNetReferenceWithMessageResource
 */
class PetriNetReferenceWithMessageResource extends AppSerializable
{

    /** @var string $data */
    public $data;

    /** @var string $error */
    public $error;

    /** @var Link $links */
    public $links;

    /** @var PetriNetReference $net */
    public $net;

    /** @var string $success */
    public $success;

}
