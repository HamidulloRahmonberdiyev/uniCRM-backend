<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'string',
            'quantity' => 'integer',
        ];
    }
}
