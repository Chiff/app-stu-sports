<?php
/**
 * DataFieldReference
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * DataFieldReference
 */
class DataFieldReference extends AppSerializable
{

    /** @var string $petriNetId */
    public $petriNetId;

    /** @var string $stringId */
    public $stringId;

    /** @var string $title */
    public $title;

    /** @var string $transitionId */
    public $transitionId;

    /** @var string $type */
    public $type;

}
