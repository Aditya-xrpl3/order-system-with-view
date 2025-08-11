<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;
        \Log::info("Checking role: User has {$userRole}, required: " . implode(',', $roles));

        // Tangani kasus umum
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect ke halaman yang sesuai dengan role
        if ($userRole === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($userRole === 'cashier') {
            return redirect('/cashier/orders');
        } else {
            // Default untuk role 'user'
            return redirect('/products');
        }
    }
}
