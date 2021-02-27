<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class GroupService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'getAllGroupsUsingGET' => 'api/group/all',
            'getGroupsOfUserUsingGET' => '/api/group/my',
        ];
    }
}

