<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\UserServiceInterface;
use App\Services\UserServiceImpl;

use App\Repository\UserRepositoryImpl;
use App\Repository\UserRepositoryInterface;


use App\Services\TransactionServiceInterface;
use App\Services\TransactionServiceImpl;

use App\Repository\TransactionRepositoryInterface;
use App\Repository\TransactionRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(UserServiceInterface::class, UserServiceImpl::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepositoryImpl::class);

        $this->app->singleton(UserServiceInterface::class, function ($app) {
            return new UserServiceImpl($app->make(UserRepositoryImpl::class));
        });

        $this->app->singleton(TransactionServiceInterface::class, function ($app) {
            return new TransactionRepositoryImpl();
        });

        $this->app->singleton(TransactionRepositoryInterface::class, function ($app) {
            return new TransactionRepositoryImpl();
        });

        $this->app->singleton(TransactionServiceInterface::class, function ($app) {
            return new TransactionServiceImpl($app->make(TransactionRepositoryImpl::class));
        });
    } 

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
