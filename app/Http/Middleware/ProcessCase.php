<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class ProcessCase {

    public function handle($request, Closure $next)
    {
        if (!$request->isMethod('post')) {
            if (preg_match('/[A-Z]/', $request->getRequestUri())) {
                return redirect(strtolower($request->getRequestUri()), 301);
            }
        }

        return $next($request);
    }
}
