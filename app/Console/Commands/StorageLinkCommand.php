<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    protected $signature = 'storage:link-custom';

    protected $description = 'Create storage link without exec';

    public function handle()
    {
        $publicStorage = public_path('storage');
        $appStorage = storage_path('app/public');

        if (file_exists($publicStorage)) {
            if (is_link($publicStorage)) {
                unlink($publicStorage);
            } else {
                $this->error('Public/storage is not a symbolic link.');
                return;
            }
        }

        try {
            symlink($appStorage, $publicStorage);
            $this->info('The [public/storage] directory has been linked.');
        } catch (\Exception $e) {
            $this->error('Failed to create symbolic link: ' . $e->getMessage());
        }
    }
}
