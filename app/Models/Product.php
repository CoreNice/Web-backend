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
        'price',       // number (decimal/integer)
        'description',
        'stock',       // number for inventory
        'image',       // filename in storage, mis: 'product-name.jpg' (no prefix)
        'created_at',
        'updated_at',
    ];

    // Cast price and stock to numeric types
    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    // Helper untuk mengembalikan public url (bila belum di-set atau gunakan default)
    public function getImageAttribute($value)
    {
        // If value is set and already a full URL, return it
        if (!empty($value)) {
            if (str_starts_with($value, 'http')) {
                return $value;
            }

            // If it's a stored path or filename, attempt to build a public storage URL
            try {
                return url(\Illuminate\Support\Facades\Storage::url($value));
            } catch (\Throwable $e) {
                return $value;
            }
        }

        // If empty, return a simple placeholder image URL
        return 'https://via.placeholder.com/600x400?text=No+Image';
    }

    // Get status based on stock
    public function getStatusAttribute()
    {
        return ($this->stock ?? 0) > 0 ? 'available' : 'out of stock';
    }

    // Auto-set timestamps
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
