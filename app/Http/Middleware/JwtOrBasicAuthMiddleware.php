<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class JwtOrBasicAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        if ($request->getUser() && $request->getPassword()) {
            $user = User::where('username', $request->getUser())->first();

            if ($user && Hash::check($request->getPassword(), $user->password)) {
                Auth::login($user);
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
