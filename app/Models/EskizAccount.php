<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskizAccount extends Model
{
    protected $fillable = [
        'email',
        'password',
        'token',
        'token_updated_at',
        'is_active'
    ];

    protected $casts = [
        'token_updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
