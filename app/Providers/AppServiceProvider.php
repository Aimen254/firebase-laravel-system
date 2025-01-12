<?php

namespace App\Providers;

use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       
$this->app->singleton(FirebaseAuth::class, function ($app) {
    $firebase = (new Factory)
        ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    return $firebase->createAuth();
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
