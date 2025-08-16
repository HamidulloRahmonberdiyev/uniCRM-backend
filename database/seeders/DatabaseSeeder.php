<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone' => '998999999999',
            'username' => 'admin',
            'password' => '12345',
        ]);

        $this->call([
            CustomerSeeder::class,
            SourceSeeder::class,
            RoleSeeder::class,
            CustomerTypeSeeder::class,
        ]);
    }
}
