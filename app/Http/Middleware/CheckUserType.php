<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$types
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->user_type, $types)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}