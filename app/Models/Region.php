<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
