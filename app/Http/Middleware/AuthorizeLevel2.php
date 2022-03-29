<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class AuthorizeLevel2
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
        if( Gate::authorize('access-level-2-for-organization', $request->route('organization')) )
        {
            return $next($request);
        }

        abort(403, 'You do not have access for this organization.');
    }
}
