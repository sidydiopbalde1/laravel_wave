<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthentificationServiceInterface;
use App\Services\AuthentificationPassport ;

class AuthCustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthentificationServiceInterface::class, function ($app) {
            return new AuthentificationPassport();
        });  
    }

    public function boot()
    {
        //
    }
}
