<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ]
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('User not registered', 422);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Login failed', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Login failed', 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return $this->successResponse('Successfully logged out', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Logout failed', 500);
        }
    }
}
