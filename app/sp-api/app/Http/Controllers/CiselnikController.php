<?php


namespace App\Http\Controllers;

use App\Http\Services\CiselnikService;
use Illuminate\Http\JsonResponse;
use JsonMapper\JsonMapper;
use Laravel\Lumen\Routing\Controller;


class CiselnikController extends Controller
{
    private JsonMapper $mapper;
    private CiselnikService $ciselnikService;

    public function __construct(JsonMapper $mapper, CiselnikService $ciselnikService, array $attributes = [])
    {
        $this->mapper = $mapper;
        $this->ciselnikService = $ciselnikService;
    }

    public function getType($type): JsonResponse
    {
        $data = $this->ciselnikService->getType($type);
        return response()->json($data);
    }

}
