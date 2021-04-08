<?php


namespace App\Http\Services\Netgrif;



use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
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

    public function deleteCaseUsingDELETE($id): MessageResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['deleteCaseUsingDELETE'], $id);
        $response = self::beginRequest()->delete($url);

        if ($response->failed()) {
            $response->throw();
        }

        $message = new MessageResource();
        $this->mapper->mapObject($response->object(), $message);
        return $message;
    }

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
        $response = self::beginRequest()->post($url, array('color' => 'black', 'netId' => env('API_INTERES_EVENT_NET_ID'), 'title' => 'event'));
        if ($response->failed()) {
            $response->throw();
        }

        $case = new CaseResource();
        $this->mapper->mapObject($response->object(), $case);

        return $case;
    }

    public function findAllByAuthorUsingPOST($authorId): EmbededCases
    {

        $url = self::getFullRequestUrl($this->apiPaths['searchUsingPOST']);
        $response = self::beginRequest()->post($url, array('author' => array('id' => $authorId)));

        if ($response->failed()) {
            $response->throw();
        }
        $cases = new EmbededCases();
        $this->mapper->mapObject($response->object(), $cases);

        return $cases;
    }

}

