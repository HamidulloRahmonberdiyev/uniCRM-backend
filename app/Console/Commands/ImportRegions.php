<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRegions extends Command
{
    protected $signature = 'import:regions';

    protected $description = 'Import regions, cities, districts, and neighborhoods from a JSON file into the database';

    public function handle()
    {
        $filePath = public_path('regions/regions.json');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $jsonData = json_decode(file_get_contents($filePath), true);

        if (!$jsonData) {
            $this->error("Invalid JSON file.");
            return;
        }

        DB::transaction(function () use ($jsonData) {
            foreach ($jsonData['regions'] as $region) {
                DB::table('regions')->updateOrInsert(
                    ['id' => $region['id']],
                    ['name' => $region['name']]
                );
            }

            foreach ($jsonData['cities'] as $city) {
                DB::table('cities')->updateOrInsert(
                    ['id' => $city['id']],
                    ['region_id' => $city['region_id'], 'name' => $city['name']]
                );
            }

            foreach ($jsonData['districts'] as $district) {
                DB::table('districts')->updateOrInsert(
                    ['id' => $district['id']],
                    ['region_id' => $district['region_id'], 'name' => $district['name']]
                );
            }

            foreach ($jsonData['quarters'] as $quarter) {
                DB::table('neighborhoods')->updateOrInsert(
                    ['id' => $quarter['id']],
                    ['district_id' => $quarter['district_id'], 'name' => $quarter['name']]
                );
            }
        });

        $this->info("Regions, cities, districts, neighborhoods data imported successfully.");
    }
}
