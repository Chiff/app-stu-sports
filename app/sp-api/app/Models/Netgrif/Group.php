<?php
/**
 * Group
 */

namespace App\Models\Netgrif;

use App\Models\AppSerializable;

/**
 * Group
 */
class Group extends AppSerializable
{

    /** @var Group[] $childGroups */
    public $childGroups;

    /** @var int $id */
    public $id;

    /** @var Member[] $members */
    public $members;

    /** @var string $name */
    public $name;

    /** @var Group $parentGroup */
    public $parentGroup;

}
