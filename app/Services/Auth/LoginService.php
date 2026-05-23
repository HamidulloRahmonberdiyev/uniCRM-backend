<?php

namespace App\Services\Auth;

use App\Helpers\Token\AuthTokenHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginService
{
  public function __construct(
    protected AuthService $authService
  ) {}

  public function attemptLogin(string $phone, string $password): array
  {
    $phone = sanitizePhone($phone);

    // $this->checkRateLimit($phone);

    $user = $this->findActiveUser($phone);

    $this->validateCredentials($user, $password, $phone);

    $this->clearRateLimit($phone);

    $tokens = AuthTokenHelper::createTokens($user);

    return [
      'access_token' => $tokens['access_token'],
      'refresh_token' => $tokens['refresh_token'],
      'user' => new UserResource($user),
      'token_type' => 'Bearer',
      'expires_in' => config('sanctum.expiration'),
    ];
  }

  public function logout($user)
  {
    $user->currentAccessToken()->delete();
  }

  protected function checkRateLimit(string $phone): void
  {
    $key = $this->getRateLimitKey($phone);

    if (RateLimiter::tooManyAttempts($key, 5)) {
      $seconds = RateLimiter::availableIn($key);
      throw new \Exception(
        'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
        Response::HTTP_TOO_MANY_REQUESTS
      );
    }
  }

  protected function findActiveUser(string $phone): ?User
  {
    return User::where('phone', $phone)->active()->first();
  }

  protected function validateCredentials(?User $user, string $password, string $phone): void
  {
    if (!$user || !Hash::check($password, $user->password)) {
      $this->incrementRateLimit($phone);

      throw ValidationException::withMessages([
        'phone' => ['The provided credentials are incorrect.'],
      ]);
    }
  }

  protected function getRateLimitKey(string $phone): string
  {
    return 'login-attempts:' . $phone;
  }

  protected function incrementRateLimit(string $phone): void
  {
    $key = $this->getRateLimitKey($phone);
    RateLimiter::hit($key, 300);
  }

  protected function clearRateLimit(string $phone): void
  {
    $key = $this->getRateLimitKey($phone);
    RateLimiter::clear($key);
  }
}
