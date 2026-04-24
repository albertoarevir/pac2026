<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Project;
use App\Policies\ProjectPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Project' => 'App\Policies\ProjectPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('eliminar-proyecto', function (User   $user) {
            return $user->hasRole('admin');
        });
    }
}