<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Emploi;

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
        Gate::define('access-admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-jobs', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('update-job', function (User $user, Emploi $emploi) {
            return $user->isAdmin() || $user->id === $emploi->user_id;
        });

        Gate::define('delete-job', function (User $user, Emploi $emploi) {
            return $user->isAdmin() || $user->id === $emploi->user_id;
        });
    }
}
