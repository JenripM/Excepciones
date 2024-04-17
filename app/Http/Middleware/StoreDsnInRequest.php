<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreDsnInRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $dsn = session('dsn', null);
        
        // Establecer $dsn en la instancia de solicitud
        $request->session()->put('dsn', $dsn);
        return $next($request);
    }
}
