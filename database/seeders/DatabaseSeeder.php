<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com',
        //     'phone' => '+998999999999',
        //     'username' => 'admin',
        //     'password' => 'password',
        // ]);

        $this->call([
            // CustomerSeeder::class,
            RegionSeeder::class,
        ]);
    }
}
