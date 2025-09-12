<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $guard = 'api';

    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'username' => 'string',
            'phone' => 'string',
            'status' => 'boolean',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isSuperAdmin()
    {
        return $this->roles->pluck('name')->contains('super_admin');
    }

    public function isAdmin()
    {
        return $this->roles->pluck('name')->containsAny(['admin', 'boss']);
    }

    public function isSupplier()
    {
        return $this->roles->pluck('name')->contains('supplier');
    }

    public function isOperator()
    {
        return $this->roles->pluck('name')->contains('operator');
    }

    public function getRoleNameAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ') ?? '';
    }

    public function scopeSupplier($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'supplier');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }
}
