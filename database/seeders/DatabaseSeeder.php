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
            'phone' => '998912500760',
            'username' => 'admin',
            'password' => '123456',
        ]);

        $this->call([
            CustomerSeeder::class,
            SourceSeeder::class,
            RoleSeeder::class,
            CustomerTypeSeeder::class,
        ]);
    }
}
