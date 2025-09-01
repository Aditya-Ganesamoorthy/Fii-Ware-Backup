<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDriver
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->role_id, [1, 3])) {
            return $next($request);
        }

        abort(403, 'Unauthorized access. Only drivers or admins are allowed.');
    }
}
