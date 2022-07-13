<?php

namespace App\Http\Middleware;

use Closure;

class AdminRoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect('admin/locations')->with('flash_message', 'У Вас нет прав на эту страницу!');
        }
        return $next($request);

    }
}
