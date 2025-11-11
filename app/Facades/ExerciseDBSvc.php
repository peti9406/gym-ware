<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExerciseDBSvc extends Facade
{
 public static function getFacadeAccessor(): string {
     return 'exercise-db-service';
 }
}
