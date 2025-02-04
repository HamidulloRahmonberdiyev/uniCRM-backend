<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    const ACTIVE = 1;
    const CANCEL = 2;
    const DONE = 3;

    protected $fillable = [
        'customer_id',
        'user_id',
        'company_id',
        'city_id',
        'district_id',
        'neighborhood_id',
        'quantity',
        'sum',
        'date',
        'address',
        'note',
        'location',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'user_id' => 'integer',
            'company_id' => 'integer',
            'city_id' => 'integer',
            'district_id' => 'integer',
            'neighborhood_id' => 'integer',
            'quantity' => 'integer',
            'sum' => 'string',
            'date' => 'date',
            'address' => 'string',
            'note' => 'string',
            'location' => 'string',
            'status' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
        return $this->belongsTo(Neighborhood::class);
    }
}
