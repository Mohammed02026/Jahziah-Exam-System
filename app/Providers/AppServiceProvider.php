<?php

namespace App\Providers;

use App\Services\Exam\AttemptService;
use App\Services\Exam\ExamService;
use App\Services\Exam\GradingService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services bindings (optional but explicit)
        $this->app->singleton(ExamService::class, fn () => new ExamService());

        $this->app->singleton(GradingService::class, fn () => new GradingService());

        $this->app->singleton(AttemptService::class, function ($app) {
            return new AttemptService($app->make(GradingService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix pagination UI when using Bootstrap (instead of Tailwind)
        Paginator::useBootstrapFive();
      
    }
}