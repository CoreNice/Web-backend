<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SiteSetting extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'site_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;
}
