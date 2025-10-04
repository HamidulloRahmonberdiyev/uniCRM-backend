<?php

namespace App\Providers;

use App\Repositories\Interfaces\NeighborhoodRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SmsTemplateRepositoryInterface;
use App\Repositories\Neighborhood\NeighborhoodRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\SmsTemplate\SmsTemplateRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(NeighborhoodRepositoryInterface::class, NeighborhoodRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(SmsTemplateRepositoryInterface::class, SmsTemplateRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
