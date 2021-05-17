<?php

namespace App\Http\Services\AS;


use App\Http\Services\SystemService;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\TasksReferences;
use App\Models\User;


class SystemAS
{
    private SystemService $systemService;

    public function __construct(
        SystemService $systemService
    )
    {
        $this->systemService = $systemService;
    }

    public function createSystemCase(): CaseResource
    {
        return $this->systemService->createSystemCase();
    }

    public function getAvaiableTasks(): TasksReferences
    {
        return $this->systemService->getRunnableTasksOfSystem();
    }
}
