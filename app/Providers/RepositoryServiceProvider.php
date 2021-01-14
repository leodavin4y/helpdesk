<?php

namespace App\Providers;

use App\Repositories\Interfaces\RequestHistoryRepositoryInterface;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use App\Repositories\RequestHistoryRepository;
use App\Repositories\RequestRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);
        $this->app->bind(RequestHistoryRepositoryInterface::class, RequestHistoryRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
