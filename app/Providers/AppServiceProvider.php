<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\CustomersInterface',
            'App\Services\CustomersService'
        );
        $this->app->bind(
            'App\Contracts\LocationsInterface',
            'App\Services\LocationsService'
        );
        $this->app->bind(
            'App\Contracts\StationsInterface',
            'App\Services\StationsService'
        );
        $this->app->bind(
            'App\Contracts\GamesInterface',
            'App\Services\GamesService'
        );
        $this->app->bind(
            'App\Contracts\AddressesInterface',
            'App\Services\AddressesService'
        );
        $this->app->bind(
            'App\Contracts\CompaniesInterface',
            'App\Services\CompaniesService'
        );
        $this->app->bind(
            'App\Contracts\CreditsInterface',
            'App\Services\CreditsService'
        );
        $this->app->bind(
            'App\Contracts\SessionsInterface',
            'App\Services\SessionsService'
        );
        $this->app->bind(
            'App\Contracts\CommandsInterface',
            'App\Services\CommandsService'
        );
        $this->app->bind(
            'App\Contracts\PingsInterface',
            'App\Services\PingsService'
        );
        $this->app->bind(
            'App\Contracts\VendWebhooksInterface',
            'App\Services\VendWebhooksService'
        );
        $this->app->bind(
            'App\Contracts\ProductsInterface',
            'App\Services\ProductsService'
        );
        $this->app->bind(
            'App\Contracts\UsersInterface',
            'App\Services\UsersService'
        );
    }
}
