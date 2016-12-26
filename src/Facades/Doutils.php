<?php

namespace Encore\Incore\Facades;

use Illuminate\Support\Facades\Facade;

class Doutils extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Encore\Incore\Doutils::class;
    }
}
