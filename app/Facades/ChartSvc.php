<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChartSvc extends Facade
{
    protected static function getFacadeAccessor(): string {
        return 'chart-service';
    }
}
