<?php
/**
 * Group
 */
namespace App\Models\Netgrif;

/**
 * Group
 */
class Group {

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
