<?php

namespace App\Providers;

use App\Oauth2\TangoCryptoClient;
use App\TangoCrypto;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TangoCryptoServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(TangoCryptoClient::class, function () {
            return new TangoCryptoClient(
                config('services.tangocrypto.account_id'),
                config('services.tangocrypto.x_api_key'),
                config('services.tangocrypto.network')
            );
        });

        $this->app->singleton(TangoCrypto::class, function ($app) {
            return new TangoCrypto($app->make(TangoCryptoClient::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            TangoCryptoClient::class,
            TangoCrypto::class,
        ];
    }
}
