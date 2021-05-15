<?php


namespace App\Http\Services;

use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\TaskService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbeddedTasks;
use App\Models\Netgrif\EmbededCases;
use App\Models\Netgrif\TaskReference;
use App\Models\User;
use JsonMapper\JsonMapper;

class SystemService
{
    private AuthenticationService $auth;
    private JsonMapper $mapper;
    private WorkflowService $workflowService;
    private TaskService $taskService;

    public function __construct(
        AuthenticationService $authService,
        WorkflowService $workflowService,
        TaskService $taskService,
        JsonMapper $mapper,
    ) {
        $this->mapper = $mapper;
        $this->workflowService = $workflowService;
        $this->auth = $authService;
        $this->taskService = $taskService;
    }


    /*
     * return cases of type System
     */
    public function getAllSystemCases(): EmbededCases
    {
        $params = array('petriNet' => array('identifier' => env('API_INTERES_SYSTEM_NED_IDENTIFIER')));
        return $this->workflowService->searchCasesElastic($params);
    }

    /*
     * Checks if any system case exists
     */
    public function systemCaseExists():bool
    {
        $exists = false;
        $systemCases = $this->getAllSystemCases()->_embedded->cases;
        if(sizeof($systemCases) > 0) {
            $exists = true;
        }
        return $exists;
    }

    /*
     * Create system case
     */
    public function createSystemCase(): CaseResource
    {
        //$userId = auth()->user()->ext_id;
        $netId = env('API_INTERES_SYSTEM_NET_ID');
        $title = "system_" . auth()->id();
        return $this->workflowService->createCaseUsingPOST($netId, $title);
    }

}
