<?php

namespace App\Providers;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        RoleRepositoryInterface::class => RoleRepository::class,
    ];

    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
