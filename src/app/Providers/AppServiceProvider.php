<?php

namespace App\Providers;

use App\Models\MSettingApp;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Stancl\Tenancy\Facades\Tenancy;
use Stancl\Tenancy\Events\TenancyInitialized;

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
        \App\Events\TenantCreated::class => [
            \App\Listeners\SetupTenantEnvironment::class,
        ],
        \App\Events\PresensiOfflineBulkEvent::class => [
            \App\Listeners\HandlePresensiOfflineBulk::class,
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
//        if (app()->environment('local')) {
//            URL::forceRootUrl(config('app.url'));
//        }

        View::composer('*', function ($view) {
            $view->with('global_setting', $this->resolveAppSetting());
        });
    }

    private function resolveAppSetting(): array
    {
        try {
            if (tenant()) {
                $setting = MSettingApp::first();
            } else {
                $setting = MSettingApp::on('central')->first();
            }

            return [
                'app_name'       => $setting->app_name ?? config('app.name'),
                'app_logo'       => $setting->app_logo ?? null,
                'app_favicon'    => $setting->app_favicon ?? null,
                'app_background' => $setting->app_background ?? null,
            ];
        } catch (\Throwable $e) {
            return [
                'app_name'       => config('app.name'),
                'app_logo'       => null,
                'app_favicon'    => null,
                'app_background' => null,
            ];
        }
    }

}
