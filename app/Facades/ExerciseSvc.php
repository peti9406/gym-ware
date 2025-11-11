<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExerciseSvc extends Facade
{
    public static function getFacadeAccessor(): string {
        return 'exercise-service';
    }
}
