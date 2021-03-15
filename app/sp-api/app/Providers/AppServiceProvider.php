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
use App\Http\Services\Netgrif\UserService as NetgrifUserService;
use App\Http\Services\Netgrif\WorkflowService;
use App\Http\Services\UserService;
use App\Http\Utils\DateUtil;
use DateTime;
use Illuminate\Support\ServiceProvider;
use JsonMapper\Cache\ArrayCache;
use JsonMapper\Handler\ClassFactoryRegistry;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapper;
use JsonMapper\Middleware\DocBlockAnnotations;
use JsonMapper\Middleware\NamespaceResolver;
use JsonMapper\Middleware\TypedProperties;
use Laravel\Lumen\Application;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // vendor services
        $this->app->singleton(JsonMapper::class, function (Application $app) {
            $classFactoryRegistry = new ClassFactoryRegistry();

            // ked budes chciet customove parsovanie tak si pridaj novu factory
            $classFactoryRegistry->addFactory(DateTime::class, static function ($value) {
                return DateUtil::customDateMapper($value);
            });

            $properyMapper = new PropertyMapper($classFactoryRegistry);

            $mapper = new JsonMapper($properyMapper);
            $mapper->push(new DocBlockAnnotations(new ArrayCache()));

            if (PHP_VERSION_ID >= 70400) {
                $mapper->push(new TypedProperties(new ArrayCache()));
            }

            $mapper->push(new NamespaceResolver());

            return $mapper;
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
