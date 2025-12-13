<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Account;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil token dari Bearer header atau cookie 'api_token'
        $token = $request->bearerToken() ?? $request->cookie('api_token');

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - Token not provided'
            ], 401);
        }

        $user = Account::where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - Invalid token'
            ], 401);
        }

        // Attach user to request if needed, or just proceed
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
