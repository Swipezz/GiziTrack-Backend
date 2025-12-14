<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCorsSingleOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    $response = $next($request);

    $response->headers->remove('Access-Control-Allow-Origin');
    $response->headers->set(
        'Access-Control-Allow-Origin',
        'https://8b471496bf14.ngrok-free.app'
    );

    return $response;
}

}
