<?php
/**
 * TransitionReference
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * TransitionReference
 */
class TransitionReference extends AppSerializable
{

    /** @var DataFieldReference $immediateData */
    public $immediateData;

    /** @var string $petriNetId */
    public $petriNetId;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

}
