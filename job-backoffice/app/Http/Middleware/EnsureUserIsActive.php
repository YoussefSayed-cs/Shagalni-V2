<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user instanceof User && ! $this->isActive($user)) {
            return $this->logout($request);
        }

        $response = $next($request);
        $user = $request->user();

        if ($user instanceof User && ! $this->isActive($user)) {
            return $this->logout($request);
        }

        return $response;
    }

    private function logout(Request $request): Response
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function isActive(User $user): bool
    {
        if (array_key_exists('is_active', $user->getAttributes())) {
            return (bool) $user->getAttribute('is_active');
        }

        return (bool) User::query()->whereKey($user->getKey())->value('is_active');
    }
}
