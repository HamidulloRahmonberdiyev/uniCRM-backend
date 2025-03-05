<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super admin'],
            ['name' => 'admin'],
            ['name' => 'operator'],
            ['name' => 'supplier'],
            ['name' => 'boss'],
            ['name' => 'user'],
        ];

        DB::table('roles')->insert($roles);
    }
}
