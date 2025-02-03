<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDetail extends Model
{
    protected $fillable = [
        'customer_id',
        'region_id',
        'city_id',
        'district_id',
        'neighborhood_id',
        'home',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'region_id' => 'integer',
            'city_id' => 'integer',
            'district_id' => 'integer',
            'neighborhood_id' => 'integer',
            'home' => 'string',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
