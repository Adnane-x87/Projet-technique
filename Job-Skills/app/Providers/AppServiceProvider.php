<?php

namespace App\Providers;

use App\Models\Emploi;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate: Check if user is admin (for dashboard access)
        Gate::define('access-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Gate: Check if user can manage jobs (create new jobs)
        Gate::define('manage-jobs', function (User $user) {
            return $user->isAdmin();
        });

        // Gate: Check if user can update a job (admin or owner)
        Gate::define('update-job', function (User $user, Emploi $emploi) {
            return $user->isAdmin() || $user->id === $emploi->user_id;
        });

        // Gate: Check if user can delete a job (admin or owner)
        Gate::define('delete-job', function (User $user, Emploi $emploi) {
            return $user->isAdmin() || $user->id === $emploi->user_id;
        });
    }
}
