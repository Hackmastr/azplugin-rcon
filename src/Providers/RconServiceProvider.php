<?php

namespace Azuriom\Plugin\Rcon\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;

class RconServiceProvider extends BasePluginServiceProvider
{
    /**
     * The plugin's global HTTP middleware stack.
     *
     * @var array
     */
    protected array $middleware = [
        // \Azuriom\Plugin\Rcon\Middleware\ExampleMiddleware::class,
    ];

    /**
     * The plugin's route middleware groups.
     *
     * @var array
     */
    protected array $middlewareGroups = [];

    /**
     * The plugin's route middleware.
     *
     * @var array
     */
    protected array $routeMiddleware = [
        // 'example' => \Azuriom\Plugin\Rcon\Middleware\ExampleRouteMiddleware::class,
    ];

    /**
     * The policy mappings for this plugin.
     *
     * @var array
     */
    protected array $policies = [
        // User::class => UserPolicy::class,
    ];

    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerMiddlewares();

        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        $this->loadViews();

        $this->loadTranslations();

        $this->registerAdminNavigation();

        Permission::registerPermissions([
            'rcon.execute' => 'rcon::admin.permissions.rcon',
        ]);
        //
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     */
    protected function adminNavigation(): array
    {
        return [
            'rcon' => [
                'name' => trans('rcon::admin.title'), // Translation of the tab name
                'icon' => 'bi bi-controller', // FontAwesome icon
                'route' => 'rcon.admin.index', // Page's route
                'permission' => 'rcon.rcon', // (Optional) Permission required to view this page
            ],
        ];
    }
}
