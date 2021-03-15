<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use JsonMapper\JsonMapper;

class WorkflowService extends AbstractNetgrifService
{
    private JsonMapper $mapper;

    public function __construct(JsonMapper $mapper)
    {
        $this->mapper = $mapper;

        $this->apiPaths = [
            'getAllUsingGET' => 'api/workflow/all',
            'createCaseUsingPOST' => 'api/workflow/case',
            'findAllByAuthorUsingPOST' => 'api/workflow/case/author/{id}',
            'countUsingPOST1' => 'api/workflow/case/count',
            'reloadTasksUsingGET' => 'api/workflow/case/reload/{id}',
            'searchUsingPOST' => 'api/workflow/case/search',
            'search2UsingPOST' => 'api/workflow/case/search2',
            'searchMongoUsingPOST' => 'api/workflow/case/search_mongo',
            'deleteCaseUsingDELETE' => 'api/workflow/case/{id}',
            'getOneUsingGET' => 'api/workflow/case/{id}',
            'getAllCaseDataUsingGET' => 'api/workflow/case/{id}/data',
            'getFileUsingGET' => 'api/workflow/case/{id}/file/{field}',
            'getFileByNameUsingGET' => 'api/workflow/case/{id}/file/{field}/{name}',
        ];
    }
    //5f86b1eff9ac3b272d6abd48

    public function getOneUsingGET($id): CaseResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['getOneUsingGET'], $id);
        $response = self::beginRequest()->get($url);

        if ($response->failed()) {
            $response->throw();
        }

        $case = new CaseResource();
        $this->mapper->mapObject($response->object(), $case);

        return $case;
    }

    public function getAllUsingGET(): EmbededCases
    {
        $url = self::getFullRequestUrl($this->apiPaths['getAllUsingGET']);
        $response = self::beginRequest()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $cases = new EmbededCases();
        $this->mapper->mapObject($response->object(), $cases);

        return $cases;
    }

    public function createCaseUsingPOST(): CaseResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['createCaseUsingPOST']);
        $response = self::beginRequest()->post($url, array('body' => array('color' => 'black', 'netId' => env('NETGRIF_NET_EVENT_ID'), 'title' => 'event')));

        if ($response->failed()) {
            $response->throw();
        }

        $cases = new CaseResource();
        $this->mapper->mapObject($response->object(), $cases);

        return $cases;
    }

}

