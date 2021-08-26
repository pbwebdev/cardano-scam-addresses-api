<?php

namespace App\Providers;

use App\Oauth2\Blockfrost;
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
        $this->app->bind(Blockfrost::class, function () {
            return new Blockfrost(config('services.blockfrost.project_id'), config('services.blockfrost.network'));
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
        return [Blockfrost::class];
    }
}
