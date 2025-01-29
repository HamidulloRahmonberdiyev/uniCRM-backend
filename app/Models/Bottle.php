<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bottle extends Model
{
    protected $fillable = [
        'label',
        'quantity',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'label' => 'string',
            'quantity' => 'integer',
            'date' => 'date',
        ];
    }
}
