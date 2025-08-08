<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'cashier') {
                return redirect('/cashier/orders');
            } else {
                return redirect('/order');
            }
        }
        return $next($request);
    }
}
