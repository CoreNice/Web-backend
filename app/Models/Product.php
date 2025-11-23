<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'price',      
        'description',
        'stock',       
        'image',      
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            if (str_starts_with($value, 'http')) {
                return $value;
            }

            try {
                return url(\Illuminate\Support\Facades\Storage::url($value));
            } catch (\Throwable $e) {
                return $value;
            }
        }

        return 'https://via.placeholder.com/600x400?text=No+Image';
    }

    public function getStatusAttribute()
    {
        return ($this->stock ?? 0) > 0 ? 'available' : 'out of stock';
    }

    public static function booted()
    {
        static::creating(function ($model) {
            if (!$model->created_at) {
                $model->created_at = now();
            }
            $model->updated_at = now();
        });

        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }
}
