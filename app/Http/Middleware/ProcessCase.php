<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ProcessCase {

    public function handle($request, Closure $next)
    {
        if (preg_match('/[A-Z]/', $request->getRequestUri())) {
            return redirect(strtolower($request->getRequestUri()));
        }

        return $next($request);
    }
}
