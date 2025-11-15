<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'items',
        'subtotal',
        'shipping_cost',
        'total',
        'customer',
        'status'
    ];

    protected $casts = [
        'items' => 'array',
        'customer' => 'array'
    ];
}
