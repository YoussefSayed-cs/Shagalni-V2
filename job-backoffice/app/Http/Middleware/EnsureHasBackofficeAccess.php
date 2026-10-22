<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasBackofficeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasAccess = false;

        if (Auth::check()) {
            $role = Auth::user()->role;
            $hasAccess = in_array($role, [UserRole::ADMIN, UserRole::COMPANY_Owner]);
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to backoffice.');
        }


        return $next($request);
    }
}
