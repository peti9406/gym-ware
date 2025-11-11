<?php

namespace App\Providers;

use App\Repositories\ExerciseDBRepository;
use App\Repositories\ExerciseRepository;
use App\Repositories\FilteringRepository;
use App\Repositories\WorkoutDetailRepository;
use App\Repositories\WorkoutPlanRepository;
use App\Repositories\WorkoutRepository;
use App\Services\ChartService;
use App\Services\ExerciseDBService;
use App\Services\ExerciseService;
use App\Services\FilteringService;
use App\Services\WorkoutPlanService;
use App\Services\WorkoutProgressionService;
use App\Services\WorkoutService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('chart-service', function ($app) {
            return new ChartService();
        });

        $this->app->singleton('exercise-db-service', function ($app) {
            return new ExerciseDbService($app->make(ExerciseDBRepository::class));
        });

        $this->app->singleton('exercise-service', function ($app) {
            return new ExerciseService($app->make(ExerciseRepository::class));
        });

        $this->app->singleton('filtering-service', function ($app) {
            return new FilteringService($app->make(FilteringRepository::class));
        });

        $this->app->singleton('workout-plan-service', function ($app) {
            return new WorkoutPlanService($app->make(WorkoutPlanRepository::class));
        });

        $this->app->singleton('workout-service', function ($app) {
            return new WorkoutService(
                $app->make(WorkoutRepository::class),
                $app->make(WorkoutDetailRepository::class),
                $app->make(WorkoutPlanRepository::class),
            );
        });

        $this->app->singleton('workout-progression-service', function ($app) {
            return new WorkoutProgressionService(
                $app->make('chart-service'),
                $app->make('workout-service'),
                $app->make('workout-plan-service'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
