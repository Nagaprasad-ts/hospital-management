<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Log the user's roles for debugging
            Log::info('User logged in:', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->toArray(),
            ]);

            // Checking role using tharinda-rodrigo/laravel-roles-permissions
            $role = $user->roles()->first()?->name;

            if ($role === 'patient') {
                return redirect('/admin/appointments');
            }

            if ($role === 'doctor') {
                return redirect('/admin/view-my-schedule');
            }

            if ($role === 'admin') {
                return redirect('/admin');
            }
        }

        return $next($request);
    }
}

