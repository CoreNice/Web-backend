<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Favorite extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'favorites';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, '_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, '_id', 'product_id');
    }
}
