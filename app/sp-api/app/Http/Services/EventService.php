<?php


namespace App\Http\Services;

use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\UserResource;
use JsonMapper\JsonMapper;

class EventService
{
    private AuthenticationService $auth;
    private JsonMapper $mapper;
    private WorkflowService $workflowService;

    public function __construct(AuthenticationService $authService, JsonMapper $mapper)
    {
        $this->mapper = $mapper;
        $this->workflowService = new WorkflowService($mapper);
        $this->auth = $authService;
    }

    public function showAllEvents(): EmbededCases
    {
        return $this->workflowService->getAllUsingGET();
    }

    public function showOneEvent($id): CaseResource
    {
        return $this->workflowService->getOneUsingGET($id);
    }

    public function createOneEvent(): CaseResource
    {
        return $this->workflowService->createCaseUsingPOST();
    }
}
