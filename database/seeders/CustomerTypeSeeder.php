<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTypeSeeder extends Seeder
{
    public function run(): void
    {
        $customer_types = [
            [
                'label' => 'Faol',
                'number' => 7,
                'color' => 'success',
                'sortable' => 1,
            ],
            [
                'label' => 'Normal',
                'number' => 10,
                'color' => 'warning',
                'sortable' => 2,
            ],
            [
                'label' => 'Passiv',
                'number' => 15,
                'color' => 'danger',
                'sortable' => 3,
            ],
        ];

        DB::table('customer_types')->insert($customer_types);
    }
}
