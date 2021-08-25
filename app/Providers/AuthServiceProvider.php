<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administrate', function (User $user) {
            return $user->isAdministrator();
        });

        if (!$this->app->routesAreCached()) {
            Passport::routes();
            Passport::tokensCan([
                'write_wallet'  => 'Manage Wallets',
                'write_website' => 'Manage Websites',
            ]);
        }
    }
}
