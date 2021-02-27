<?php


namespace App\Http\Services\Netgrif;


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
}

