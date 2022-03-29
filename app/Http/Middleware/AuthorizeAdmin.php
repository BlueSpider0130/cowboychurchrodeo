<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeAdmin
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
        if( $request->user()->isSuper()  ||  $request->user()->isAdmin() )
        {
            return $next($request);
        }

        abort(403);
    }
}
