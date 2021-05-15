<?php

namespace App\Http\Services\AS;


use App\Http\Services\SystemService;
use App\Models\Netgrif\CaseResource;


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
}
