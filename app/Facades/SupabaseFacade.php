<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SupabaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SB';
    }
}