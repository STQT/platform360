<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
public function boot(Request $request)
    {
      if(config('app.env') === 'production') {
          \URL::forceScheme('https');
      }

      Schema::defaultStringLength(191);

       app()->setLocale($request->segment(1));
    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}