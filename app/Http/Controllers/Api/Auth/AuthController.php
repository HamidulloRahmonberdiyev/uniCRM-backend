<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Token\AuthTokenHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiJsonResponceTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->registerUser($request->validated());

            $tokens = AuthTokenHelper::createTokens($user);

            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration'),
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('User not registered', 422);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $tokens = AuthTokenHelper::createTokens($user);

        return response()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        try {
            $refreshToken = JWTAuth::setToken($request->refresh_token)->getToken();

            JWTAuth::setToken($refreshToken)->checkOrFail();

            $user = JWTAuth::setToken($refreshToken)->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Invalid refresh token'], 401);
            }

            JWTAuth::setToken($refreshToken)->invalidate();

            $tokens = AuthTokenHelper::createTokens($user);

            return response()->json([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60,
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Refresh token expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token error'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse('Logged out successfully');
    }

    public function profile()
    {
        $user = Auth::user();
        return new UserResource($user);
    }
}
