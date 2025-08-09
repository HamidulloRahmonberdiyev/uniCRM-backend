<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    const ACTIVE = 1;
    const CANCEL = 2;
    const DONE = 3;

    protected $fillable = [
        'customer_id',
        'product_id',
        'user_id',
        'supplier_id',
        'company_id',
        'city_id',
        'district_id',
        'neighborhood_id',
        'source_id',
        'quantity',
        'sum',
        'date',
        'address',
        'note',
        'latitude',
        'longitude',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'product_id' => 'integer',
            'user_id' => 'integer',
            'supplier_id' => 'integer',
            'company_id' => 'integer',
            'city_id' => 'integer',
            'district_id' => 'integer',
            'neighborhood_id' => 'integer',
            'source_id' => 'integer',
            'quantity' => 'integer',
            'sum' => 'string',
            'date' => 'date',
            'address' => 'string',
            'note' => 'string',
            'status' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        Order::observe(OrderObserver::class);

        static::created(function ($order) {
            if ($order->customer) {
                $order->customer->update(['type_id' => CustomerType::ACTIVE->value]);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
