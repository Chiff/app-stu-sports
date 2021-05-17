<?php


namespace App\Http\Controllers;


use App\Http\Services\SystemService;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class SystemController extends Controller
{
    private SystemService $systemService;

    public function __construct(SystemService $systemService)
    {
        $this->middleware('auth');
        $this->systemService = $systemService;
    }


    public function getActiveTasks(): JsonResponse
    {
        $tasks = $this->systemService->getRunnableTasksOfSystem();
        return response()->json($tasks, 200);
    }

    public function runTask(string $stringId): JsonResponse
    {
        $result = $this->systemService->runTask($stringId);
        return response()->json($result, 200);
    }

}
