<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate

{
    public function handle(Request $request, Closure $next)
    {
    if (!Auth::check()) {
        return redirect()->route('signin');
    }
    return $next($request);
    }

   protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('signin');
    }

}
