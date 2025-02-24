<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'region_id',
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

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function neighborhoods(): HasMany
    {
        return $this->hasMany(Neighborhood::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
