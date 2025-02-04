<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sorter extends Model
{
    protected $fillable = [
        'label',
        'number'
    ];

    protected function casts(): array
    {
        return [
            'label' => 'string',
            'number' => 'integer',
        ];
    }
}
