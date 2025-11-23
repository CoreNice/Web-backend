<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'api_tokens',
        'avatarUrl'
    ];

    protected $hidden = ['password', 'api_tokens'];

    public function generateApiToken()
    {
        $token = Str::random(64);

        $tokens = $this->api_tokens ?? [];
        $tokens[] = $token;

        $this->api_tokens = $tokens;
        $this->save();

        return $token;
    }

    public function revokeToken($token)
    {
        $tokens = $this->api_tokens ?? [];
        $this->api_tokens = array_values(array_filter($tokens, fn($t) => $t !== $token));
        $this->save();
    }

    public static function findByToken($token)
    {
        return self::where('api_tokens', $token)->first();
    }
}
