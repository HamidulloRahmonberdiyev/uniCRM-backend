<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDetail extends Model
{
    protected $fillable = [
        'customer_id',
        'region_id',
        'district_id',
        'neighborhood_id',
        'home',
        'bottle_count',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'region_id' => 'integer',
            'district_id' => 'integer',
            'neighborhood_id' => 'integer',
            'home' => 'string',
            'bottle_count' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }
}
