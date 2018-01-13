<?php

namespace Hanoivip\PassportGuard;

use Hanoivip\PassportGuard\Extensions\AccessTokenGuard;
use Hanoivip\PassportGuard\Extensions\TokenToUserProvider;
//use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/passport.php' => config_path('passport.php'),
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ]);
        
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');

        Auth::extend('access_token', function ($app, $name, array $config) {
            // automatically build the DI, put it as reference
            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');
            
            return new AccessTokenGuard($userProvider, $request, $config);
        });
    }
}
