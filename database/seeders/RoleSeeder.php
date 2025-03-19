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
            ['name' => 'super_admin', 'guard_name' => 'api'],
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'operator', 'guard_name' => 'api'],
            ['name' => 'supplier', 'guard_name' => 'api'],
            ['name' => 'boss', 'guard_name' => 'api'],
            ['name' => 'user', 'guard_name' => 'api'],
        ];

        DB::table('roles')->insert($roles);
    }
}
