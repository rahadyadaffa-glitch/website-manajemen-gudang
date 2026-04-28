<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMinimarketAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect('/login');
        }

        if ($user->isSuperadmin()) {
            return $next($request);
        }

        if (is_null($user->minimarket_id)) {
            abort(403, 'No minimarket assigned.');
        }

        return $next($request);
    }
}
