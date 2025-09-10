<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    protected $signature = 'storage-link';

    protected $description = 'Create storage link without exec';

    public function handle()
    {
        if (file_exists(public_path('storage'))) {
            return $this->error('Storage link already exists.');
        }

        $this->laravel->make('files')->link(
            storage_path('app/public'),
            public_path('storage')
        );

        $this->info('Storage link created successfully.');
    }
}
