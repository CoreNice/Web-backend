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
        'image_url',   // optional cached public url
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
        // Jika ada nilai image, kembalikan URL
        if (!empty($value)) {
            // jika sudah absolute url, kembalikan langsung
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            return url(\Illuminate\Support\Facades\Storage::url('public/products/' . $value));
        }

        // Jika kosong, return default image
        return url(\Illuminate\Support\Facades\Storage::url('public/pout.jpg'));
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
