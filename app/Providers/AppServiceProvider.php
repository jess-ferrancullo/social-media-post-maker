<?php

namespace App\Providers;

use App\Repositories\FacebookRepository;
use App\Repositories\FacebookRepositoryInterface;
use App\Repositories\InstagramRepository;
use App\Repositories\InstagramRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FacebookRepositoryInterface::class, FacebookRepository::class);
        $this->app->bind(InstagramRepositoryInterface::class, InstagramRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
