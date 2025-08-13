<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Listeners\RecordFailedLoginAttempt;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::defaultView('vendor.pagination.metronic');
        if ($this->app->isLocal()) {
            Model::shouldBeStrict(true);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Event::listen(Login::class, LogSuccessfulLogin::class);
        \Event::listen(Failed::class, RecordFailedLoginAttempt::class);
    }
}
