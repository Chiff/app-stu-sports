<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\MessageResource;
use JsonMapper\JsonMapper;
use Symfony\Component\VarDumper\Cloner\Data;

class WorkflowService extends AbstractNetgrifService
{
    private JsonMapper $mapper;
    private \App\Http\Services\UserService $userService;

    public function __construct(JsonMapper $mapper, \App\Http\Services\UserService $userService)
    {
        $this->mapper = $mapper;
        $this->userService = $userService;

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
        $response = self::beginRequestAsSystem()->delete($url);

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
        $response = self::beginRequestAsSystem()->get($url);

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
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $cases = new EmbededCases();
        $this->mapper->mapObject($response->object(), $cases);
        return $cases;
    }

    public function createCaseUsingPOST(string $netId, string $caseTitle): CaseResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['createCaseUsingPOST']);

        $credentials = $this->userService->decodeCredentials();

        // $userId = auth()->user()->ext_id

        $response = self::beginRequestAsUser($credentials)->post($url, array('color' => 'black', 'netId' => $netId, 'title' => $caseTitle));
        if ($response->failed()) {
            $response->throw();
        }

        $case = new CaseResource();
        $this->mapper->mapObject($response->object(), $case);

        return $case;
    }

    public function searchCasesElastic(array $parameters): EmbededCases
    {
        $url = self::getFullRequestUrl($this->apiPaths['searchUsingPOST']);
        $response = self::beginRequestAsSystem()->post($url, $parameters);

        if ($response->failed()) {
            $response->throw();
        }
        $cases = new EmbededCases();
        $this->mapper->mapObject($response->object(), $cases);

        return $cases;
    }

    public function findAllByAuthorUsingPOST($authorId): EmbededCases
    {

        $url = self::getFullRequestUrl($this->apiPaths['searchUsingPOST']);
        $response = self::beginRequestAsSystem()->post($url, array('author' => array('id' => $authorId)));

        if ($response->failed()) {
            $response->throw();
        }
        $cases = new EmbededCases();
        $this->mapper->mapObject($response->object(), $cases);

        return $cases;
    }

}

