<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Neighborhood extends Model
{
    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'city_id',
        'district_id',
        'name',
        'second_name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'city_id' => 'integer',
            'district_id' => 'integer',
            'name' => 'string',
            'second_name' => 'string',
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
