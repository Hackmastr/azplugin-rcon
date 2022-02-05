<?php

namespace Azuriom\Plugin\Rcon\Providers;

use Azuriom\Extensions\Plugin\BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * @var string
     */
    protected $namespace = 'Azuriom\Plugin\Rcon\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function loadRoutes()
    {
        Route::prefix('admin/'.$this->plugin->id)
            ->middleware('admin-access')
            ->namespace($this->namespace.'\Admin')
            ->name($this->plugin->id.'.admin.')
            ->group(plugin_path($this->plugin->id.'/routes/admin.php'));
    }
}
