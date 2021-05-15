<?php


namespace App\Http\Services;

use App\Http\Services\Netgrif\WorkflowService;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;

class SystemService
{
    private WorkflowService $workflowService;

    public function __construct(
        WorkflowService $workflowService,
    ) {
        $this->workflowService = $workflowService;
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
