<?php

namespace App\Http\Controllers\Api\Auth;

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
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

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

            $accessToken = $user->createToken('access-token', ['*'], now()->addDays(7))->plainTextToken;
            $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addDays(365))->plainTextToken;

            return response()->json([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
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

        $accessToken = $user->createToken('access-token', ['*'], now()->addDays(7))->plainTextToken;
        $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addDays(180))->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $token = PersonalAccessToken::findToken($request->refresh_token);

        if (!$token || !$token->can('refresh')) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        $token->tokenable->tokens()->where('name', 'access-token')->delete();

        $accessToken = $token->tokenable->createToken('access-token', ['*'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
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
