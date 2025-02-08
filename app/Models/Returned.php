<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Returned extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'quantity',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'customer_id' => 'integer',
            'quantity' => 'integer',
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
