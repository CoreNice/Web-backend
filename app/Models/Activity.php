<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activities';

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'image',
        'image_url',
        'status', // 'upcoming' or 'past'
        'created_at',
        'updated_at',
    ];

    // Helper untuk mengembalikan public url (bila belum di-set)
    public function getImageUrlAttribute($value)
    {
        if (!empty($value)) {
            // jika sudah absolute url, kembalikan langsung
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            return url(\Illuminate\Support\Facades\Storage::url($value));
        }
        return null;
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

