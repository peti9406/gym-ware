<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkoutPlanSvc extends Facade
{
    protected static function getFacadeAccessor(): string {
        return 'workout-plan-service';
    }
}
