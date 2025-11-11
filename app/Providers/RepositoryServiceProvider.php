<?php

namespace App\Providers;

use App\Repositories\ExerciseDBRepository;
use App\Repositories\ExerciseRepository;
use App\Repositories\FilteringRepository;
use App\Repositories\WorkoutDetailRepository;
use App\Repositories\WorkoutPlanRepository;
use App\Repositories\WorkoutRepository;
use App\Services\WorkoutProgressionService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WorkoutPlanRepository::class, function ($app) {
            return new WorkoutPlanRepository();
        });

        $this->app->singleton(WorkoutRepository::class, function ($app) {
            return new WorkoutRepository();
        });

        $this->app->singleton(WorkoutDetailRepository::class, function ($app) {
            return new WorkoutDetailRepository();
        });

        $this->app->singleton(ExerciseRepository::class, function ($app) {
            return new ExerciseRepository();
        });

        $this->app->singleton(ExerciseDBRepository::class, function ($app) {
            return new ExerciseDBRepository();
        });

        $this->app->singleton(FilteringRepository::class, function ($app) {
            return new FilteringRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
