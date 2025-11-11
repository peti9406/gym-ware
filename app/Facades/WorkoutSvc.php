<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkoutSvc extends Facade
{
    public static function getFacadeAccessor(): string {
        return 'workout-service';
    }
}
