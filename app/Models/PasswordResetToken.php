<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token',
        'expires_at'
    ];

    public $timestamps = false;

    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
