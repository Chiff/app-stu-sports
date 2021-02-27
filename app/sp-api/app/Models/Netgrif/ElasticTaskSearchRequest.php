<?php
/**
 * ElasticTaskSearchRequest
 */
namespace App\Models\Netgrif;

/**
 * ElasticTaskSearchRequest
 */
class ElasticTaskSearchRequest {

    /** @var TaskSearchCaseRequest $case */
    public $case;

    /** @var string $fullText */
    public $fullText;

    /** @var string $group */
    public $group;

    /** @var string $process */
    public $process;

    /** @var string $query */
    public $query;

    /** @var string $role */
    public $role;

    /** @var string $title */
    public $title;

    /** @var string $transitionId */
    public $transitionId;

    /** @var int $user */
    public $user;

}
