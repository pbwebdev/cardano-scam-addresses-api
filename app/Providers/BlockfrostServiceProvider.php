<?php

namespace App\Providers;

use App\Blockfrost;
use App\Oauth2\BlockfrostClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BlockfrostServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BlockfrostClient::class, function () {
            return new BlockfrostClient(
                config('services.blockfrost.project_id'),
                config('services.blockfrost.network')
            );
        });

        $this->app->singleton(Blockfrost::class, function ($app) {
            return new Blockfrost($app->make(BlockfrostClient::class));
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
            BlockfrostClient::class,
            Blockfrost::class,
        ];
    }
}
