<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'password',
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
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function isSuperAdmin()
    {
        return $this->roles->contains(function ($value) {
            return in_array($value->name, ['super admin']);
        });
    }

    public function isAdmin()
    {
        return $this->roles->contains(function ($value) {
            return in_array($value->name, ['admin', 'boss']);
        });
    }

    public function isSupplier()
    {
        return $this->roles->contains(function ($value) {
            return in_array($value->name, ['supplier']);
        });
    }

    public function isOperator()
    {
        return $this->roles->contains(function ($value) {
            return in_array($value->name, ['operator']);
        });
    }

    public function getRoleNameAttribute(): string
    {
        return optional($this->roles->first())->name === 'supplier'
            ? 'Yetkazib beruvchi'
            : optional($this->roles->first())->name ?? 'Xodim';
    }
}
