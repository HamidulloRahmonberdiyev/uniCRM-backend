<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $customer = Customer::create([
                'user_id' => 1,
                'company_id' => rand(1, 5),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'middle_name' => fake()->lastName(),
                'date_of_birth' => fake()->date(),
                'phone' => fake()->phoneNumber(),
                'phone2' => fake()->optional()->phoneNumber(),
                'status' => 1,
            ]);

            CustomerDetail::create([
                'customer_id' => $customer->id,
                'region_id' => rand(1, 12),
                'city_id' => rand(1, 12),
                'district_id' => rand(1, 50),
                'neighborhood_id' => rand(1, 100),
                'home' => fake()->address(),
            ]);
        }
    }
}
