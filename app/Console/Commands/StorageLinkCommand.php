<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageLinkCommand extends Command
{
    protected $signature = 'storage:link-custom';

    protected $description = 'Create storage link without exec';

    public function handle()
    {
        $publicStorage = public_path('storage');
        $appStorage = storage_path('app/public');

        if (File::exists($publicStorage)) {
            File::deleteDirectory($publicStorage);
        }

        try {
            File::copyDirectory($appStorage, $publicStorage);
            $this->info('The [public/storage] directory has been copied (symlink disabled on server).');
        } catch (\Exception $e) {
            $this->error('Failed: ' . $e->getMessage());
        }
    }
}
