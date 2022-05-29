<?php


namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Auth;


class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        return $next($request);
    }
}
