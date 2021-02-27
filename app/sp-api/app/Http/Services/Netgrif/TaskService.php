<?php


namespace App\Http\Services\Netgrif;


class TaskService extends AbstractNetgrifService
{
    public function __construct()
    {
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

    public function test()
    {
        $url = self::getFullRequestUrl($this->apiPaths['saveFilesUsingPOST'], 1, 1);
        var_dump($url);
    }
}

