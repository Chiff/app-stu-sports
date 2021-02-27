<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class DashboardService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'getAggregationByQueryUsingPOST' => 'api/dashboard/search',
        ];
    }
}
