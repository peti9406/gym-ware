<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WorkoutProgressionSvc extends Facade
{
    public static function getFacadeAccessor(): string {
        return 'workout-progression-service';
    }
}
