<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TempDirectoryManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Support\TempDirectoryManager::class;
    }
}
