<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HelpdeskAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('helpdesk_user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
