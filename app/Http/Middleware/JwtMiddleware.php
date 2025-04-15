<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // Get the token from the Authorization header and authenticate
//            if (!$user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['error' => 'User not found'], 404);
//            }
        } catch (JWTException $e) {
            // Catch specific token errors
            if ($e instanceof TokenExpiredException) {
                return response()->json(['error' => 'Token has expired'], 401);
            } elseif ($e instanceof TokenInvalidException) {
                return response()->json(['error' => 'Token is invalid'], 401);
            } elseif ($e instanceof TokenBlacklistedException) {
                return response()->json(['error' => 'Token has been blacklisted'], 401);
            } else {
                return response()->json(['error' => 'Token not provided'], 401);
            }
        }

        return $next($request);

        return $next($request);
    }
}
