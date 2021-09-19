<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class FrameworkUpgrade {

    public function handle($request, Closure $next)
    {
        if (!Cookie::has('has_migrated')) {
            Cookie::unqueue('city');
            Cookie::queue(Cookie::forever('has_migrated', '1'));
        }

        return $next($request);
    }
}
