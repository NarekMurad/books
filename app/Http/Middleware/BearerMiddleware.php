<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class BearerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        $user = User::where('token', $token)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied!'
            ], 401);
        }

        return $next($request);
    }
}
