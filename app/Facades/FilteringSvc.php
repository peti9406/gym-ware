<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FilteringSvc extends Facade
{
    public static function getFacadeAccessor(): string {
        return 'filtering-service';
    }
}
