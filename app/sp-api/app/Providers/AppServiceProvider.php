<?php

namespace App\Providers;

use App\Http\Services\EventService;
use App\Http\Services\Netgrif\AuthenticationService;
use App\Http\Services\Netgrif\DashboardService;
use App\Http\Services\Netgrif\ElasticSearchService;
use App\Http\Services\Netgrif\FilterService;
use App\Http\Services\Netgrif\GroupService;
use App\Http\Services\Netgrif\PetriNetService;
use App\Http\Services\Netgrif\TaskService;
use App\Http\Services\Netgrif\UserService;
use App\Http\Services\Netgrif\WorkflowService;
use Illuminate\Support\ServiceProvider;
use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use Laravel\Lumen\Application;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // vendor services
        $this->app->singleton(JsonMapper::class, function (Application $app) {
            return (new JsonMapperFactory())->bestFit();
        });

        // netgrif services
        $this->app->singleton(AuthenticationService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(ElasticSearchService::class);
        $this->app->singleton(FilterService::class);
        $this->app->singleton(GroupService::class);
        $this->app->singleton(PetriNetService::class);
        $this->app->singleton(TaskService::class);
        $this->app->singleton(UserService::class);
        $this->app->singleton(WorkflowService::class);

        // sp services
        $this->app->singleton(EventService::class);
    }
}
