<?php

namespace App\Providers;

use App\Dto\CiselnikDTO;
use App\Http\Services\AS\CiselnikAS;
use App\Http\Services\AS\EventAS;
use App\Http\Services\AS\UserTeamAS;
use App\Http\Services\CiselnikService;
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
use App\Http\Services\TeamService;
use App\Http\Services\UserService;
use App\Http\Utils\DateUtil;
use DateTime;
use Illuminate\Support\ServiceProvider;
use JsonMapper\Cache\ArrayCache;
use JsonMapper\Handler\ClassFactoryRegistry;
use JsonMapper\Handler\PropertyMapper;
use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
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

            $tmpMapper = (new JsonMapperFactory())->bestFit();
            // ked budes chciet customove parsovanie tak si pridaj novu factory
            $classFactoryRegistry->addFactory(DateTime::class, static function ($value) {
                return DateUtil::customDateMapper($value);
            });
            $classFactoryRegistry->addFactory(CiselnikDTO::class, static function ($value) use ($tmpMapper) {
                return CiselnikAS::toDto($value, $tmpMapper);
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
        $this->app->singleton(NetgrifUserService::class);
        $this->app->singleton(WorkflowService::class);

        // sp services
        $this->app->singleton(EventService::class);
        $this->app->singleton(UserService::class);
        $this->app->singleton(TeamService::class);
        $this->app->singleton(CiselnikService::class);

        // application service
        $this->app->singleton(UserTeamAS::class);
        $this->app->singleton(EventAS::class);
        $this->app->singleton(CiselnikAS::class);
    }
}
