<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\RolePage;

class RolePageAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $routeName = Route::currentRouteName();

        // Check if role_id has access to this page_name
        $hasAccess = RolePage::where('role_id', $user->role_id)
                             ->where('page_name', $routeName)
                             ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this page.');
        }

        return $next($request);
    }
}
