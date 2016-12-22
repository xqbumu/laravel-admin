<?php

namespace Encore\Incore\Facades;

use Illuminate\Support\Facades\Facade;

class Docore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Encore\Incore\Docore::class;
    }
}
