<?php


namespace App\Http\Services\Netgrif;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class AbstractNetgrifService
{
    protected array $apiPaths;

    public static function beginRequest(): PendingRequest
    {
        return Http::withBasicAuth(
            env('API_INTERES_USERNAME'),
            env('API_INTERES_AIS_ID')
        );
    }

    public static function getFullRequestUrl(string $apiPath, string ...$pathParams): string
    {
        $apiPath = preg_replace_array('({[A-Za-z]+})', $pathParams, $apiPath);
        return env('API_INTERES_URL') . $apiPath;
    }
}
