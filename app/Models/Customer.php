<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    const ACTIVE = 1;
    const DEACTIVE = 0;

    protected $fillable = [
        'user_id',
        'company_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'phone',
        'phone2',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'company_id' => 'integer',
            'first_name' => 'string',
            'last_name' => 'string',
            'middle_name' => 'string',
            'date_of_birth' => 'date',
            'phone' => 'string',
            'phone2' => 'string',
            'status' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customerDetails(): HasMany
    {
        return $this->hasMany(CustomerDetail::class);
    }
}
