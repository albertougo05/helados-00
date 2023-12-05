<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        // Hablita en views y en middlewares, al usuario segun rol (role) y permiso (ability)
        Gate::before(function ($user, $ability)
        {
            # Retorna verdadero (true) si el usuario tiene ese permiso (ability)
            return $user->abilities()->contains($ability);
        });
    }
}
