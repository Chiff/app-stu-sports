<?php


namespace App\Http\Services\Netgrif;


use JsonMapper\JsonMapper;

class ElasticSearchService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'reindexUsingPOST' => 'api/elastic/reindex',
        ];
    }
}
