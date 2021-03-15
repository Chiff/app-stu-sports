<?php
/**
 * ProcessRole
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * ProcessRole
 */
class ProcessRole extends AppSerializable
{

    /** @var string $description */
    public $description;

    /** @var string $importId */
    public $importId;

    /** @var string $name */
    public $name;

    /** @var string $netImportId */
    public $netImportId;

    /** @var string $netStringId */
    public $netStringId;

    /** @var string $netVersion */
    public $netVersion;

    /** @var string $stringId */
    public $stringId;

}
