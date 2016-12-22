<?php

namespace Intendant\{$stub_intendant_zone_upper}\Models\System;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $connection = 'others';

    protected $fillable = array(
        'caption', 'alt',
        'text', 'size', 'type',
        'width', 'height', 'url',
        'thumb', 'delete_url', 'error',
    );

}
