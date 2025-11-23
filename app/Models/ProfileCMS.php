<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProfileCMS extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'profile_cms';

    protected $fillable = [
        'name',
        'description',
        'longDescription',
        'icon',
        'color',
        'image',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getKeyName()
    {
        return '_id';
    }

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

        return null;
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
