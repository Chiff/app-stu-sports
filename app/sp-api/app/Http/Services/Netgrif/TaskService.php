<?php


namespace App\Http\Services\Netgrif;


use App\Models\Netgrif\LocalisedTaskResource;
use App\Models\Netgrif\MessageResource;
use App\Models\Netgrif\TaskReference;
use JsonMapper\JsonMapper;

class TaskService extends AbstractNetgrifService
{

    private JsonMapper $mapper;
    private \App\Http\Services\UserService $userService;

    public function __construct(JsonMapper $mapper, \App\Http\Services\UserService $userService)
    {
        $this->mapper = $mapper;
        $this->userService = $userService;

        $this->apiPaths = [
            'getAllUsingGET' => 'api/task',
            'assignUsingGET' => 'api/task/assign/{id}',
            'cancelUsingGET' => 'api/task/cancel/{id}',
            'getAllByCasesUsingPOST' => 'api/task/case',
            'getTasksOfCaseUsingGET' => 'api/task/case/{id}',
            'countUsingPOST' => 'api/task/count',
            'delegateUsingPOST' => 'api/task/delegate/{id}',
            'finishUsingGET' => 'api/task/finish/{id}',
            'getMyUsingGET' => 'api/task/my',
            'getMyFinishedUsingGET' => 'api/task/my/finished',
            'searchUsingPOST1' => 'api/task/search',
            'searchElasticUsingPOST' => 'api/task/search_es',
            'getOneUsingGET' => 'api/task/{id}',
            'getDataUsingGET' => 'api/task/{id}/data',
            'setDataUsingPOST' => 'api/task/{id}/data',
            'deleteFileUsingDELETE' => 'api/task/{id}/file/{field}',
            'getFileUsingGET' => 'api/task/{id}/file/{field}',
            'saveFileUsingPOST' => 'api/task/{id}/file/{field}',
            'deleteNamedFileUsingDELETE' => 'api/task/{id}/file/{field}/{name}',
            'getNamedFileUsingGET' => 'api/task/{id}/file/{field}/{name}',
            'getFilePreviewUsingGET' => 'api/task/{id}/file_preview/{field}',
            'saveFilesUsingPOST' => 'api/task/{id}/files/{field}',
        ];
    }

    public function assignUsingGET($task_id): MessageResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['assignUsingGET'], $task_id);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $message = new MessageResource();
        $this->mapper->mapObject($response->object(), $message);
        return $message;
    }

    public function cancelUsingGET($task_id): MessageResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['cancelUsingGET'], $task_id);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $message = new MessageResource();
        $this->mapper->mapObject($response->object(), $message);
        return $message;
    }

    public function finishUsingGET($task_id): MessageResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['finishUsingGET'], $task_id);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $message = new MessageResource();
        $this->mapper->mapObject($response->object(), $message);
        return $message;
    }

    public function getOneUsingGET($task_id): LocalisedTaskResource
    {
        $url = self::getFullRequestUrl($this->apiPaths['getOneUsingGET'], $task_id);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $task = new LocalisedTaskResource();
        $this->mapper->mapObject($response->object(), $task);
        return $task;
    }

    public function getTasksOfCaseUsingGET($case_id): TaskReference
    {
        $url = self::getFullRequestUrl($this->apiPaths['getTasksOfCaseUsingGET'], $case_id);
        $response = self::beginRequestAsSystem()->get($url);

        if ($response->failed()) {
            $response->throw();
        }
        $task = new TaskReference();
        $this->mapper->mapObject($response->object(), $task);
        return $task;
    }


}

