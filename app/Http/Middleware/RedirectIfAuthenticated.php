<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, \Closure $next): Response
    {
        // Jika user sudah login DAN user sedang mengakses halaman tamu (guest)
        if (Auth::check() && $this->isGuestRoute($request)) {
            return redirect('/UsulanPenelitian');
        }
        return $next($request);
    }

    private function isGuestRoute(Request $request): bool
    {
        $guestRoutes = [
            'signin',
            'signup',
            'register',
        ];

        return in_array($request->route()->getName(), $guestRoutes);
    }
}
