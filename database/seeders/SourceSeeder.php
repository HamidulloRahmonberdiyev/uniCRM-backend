<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        Source::insert([
            ['id' => 1, 'name' => 'Aloqa operator'],
            ['id' => 2, 'name' => 'Telegram bot'],
            ['id' => 3, 'name' => 'Mobil ilova'],
        ]);
    }
}
