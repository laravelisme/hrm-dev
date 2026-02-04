<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

    protected $listen = [
        \App\Events\HariLiburCreated::class => [
            \App\Listeners\AttachCompaniesToHariLibur::class,
        ],
        \App\Events\HariLiburUpdated::class => [
            \App\Listeners\SyncCompaniesToHariLibur::class,
        ],
        \App\Events\GenerateSaldoCutiTahunan::class => [
            \App\Listeners\GenerateSaldoCutiTahunanListener::class,
        ],
    ];


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
        Paginator::useBootstrap();
        Paginator::currentPathResolver(function () {
            return url()->current();
        });
        if (app()->environment('local')) {
            URL::forceRootUrl(config('app.url'));
        }

    }
}
