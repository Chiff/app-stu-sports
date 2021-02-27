<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class FilterService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'createFilterUsingPOST' => 'api/filter',
            'searchUsingPOST' => 'api/filter/search',
            'deleteFilterUsingDELETE' => 'api/filter/{id}',
        ];
    }
}

