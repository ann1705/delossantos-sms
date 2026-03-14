<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Feature: Role-Based Access Control
         * This gate allows users with the 'admin' role to access the
         * Scholarship Registry and Management features.
         */
        Gate::define('access-admin', function (User $user) {
            return $user->role === 'admin';
        });

        /**
         * Optional: Student Gate
         * Use this if you want to strictly lock the student dashboard
         * so admins can't accidentally submit applications.
         */
        Gate::define('access-student', function (User $user) {
            return $user->role === 'student';
        });
    }
}
