<?php

namespace App\Helpers\Token;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthTokenHelper
{
    public static function createTokens($user, $accessDays = 60, $refreshDays = 90)
    {
        $accessToken = JWTAuth::claims([
            'exp' => now()->addDays($accessDays)->timestamp,
            'type' => 'access'
        ])->fromUser($user);

        $refreshToken = JWTAuth::claims([
            'exp' => now()->addDays($refreshDays)->timestamp,
            'type' => 'refresh'
        ])->fromUser($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => $accessDays * 24 * 60 * 60
        ];
    }

    public static function refreshTokens($refreshToken)
    {
        $payload = JWTAuth::setToken($refreshToken)->getPayload();

        if ($payload->get('type') !== 'refresh') {
            throw new \Exception('Invalid token type');
        }

        $user = JWTAuth::setToken($refreshToken)->authenticate();
        JWTAuth::setToken($refreshToken)->invalidate();

        return self::createTokens($user);
    }

    public static function isRefreshToken($token)
    {
        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            return $payload->get('type') === 'refresh';
        } catch (\Exception $e) {
            return false;
        }
    }
}
