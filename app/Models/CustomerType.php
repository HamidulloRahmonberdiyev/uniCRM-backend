<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerType extends Model
{
    protected $fillable = [
        'label',
        'number',
        'color',
        'sortable',
    ];

    protected function casts(): array
    {
        return [
            'label' => 'string',
            'number' => 'integer',
            'color' => 'string',
            'sortable' => 'integer',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'type_id', 'id');
    }
}
