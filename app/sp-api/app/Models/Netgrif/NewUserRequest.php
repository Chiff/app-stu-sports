<?php
/**
 * NewUserRequest
 */
namespace App\Models\Netgrif;

/**
 * NewUserRequest
 */
class NewUserRequest {

    /** @var string $email */
    public $email;

    /** @var int[] $groups */
    public $groups;

    /** @var string[] $processRoles */
    public $processRoles;

}
