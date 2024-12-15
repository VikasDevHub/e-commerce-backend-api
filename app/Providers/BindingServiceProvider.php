<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BindingServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            'App\Services\v1\Service\RoleService',
            'App\Services\v1\Impl\RoleServiceImpl'
        );

        $this->app->bind(
            'App\Services\v1\Service\UserService',
            'App\Services\v1\Impl\UserServiceImpl'
        );

        $this->app->bind(
            'App\Services\v1\Service\TagService',
            'App\Services\v1\Impl\TagServiceImpl'
        );

        $this->app->bind(
            'App\Services\v1\Service\ProductService',
            'App\Services\v1\Impl\ProductServiceImpl'
        );

    }

        /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
