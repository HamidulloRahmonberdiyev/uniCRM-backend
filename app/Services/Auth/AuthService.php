<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function registerUser(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);

            if (isset($data['role'])) {
                $user->roles()->attach($data['role']);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAccessToken(User $user)
    {
        $accessClaims = ['exp' => now()->addDay(1)->timestamp];
        $refreshClaims = ['exp' => now()->addDays(180)->timestamp, 'type' => 'refresh'];

        return [
            'access_token' => JWTAuth::claims($accessClaims)->fromUser($user),
            'refresh_token' => JWTAuth::claims($refreshClaims)->fromUser($user),
        ];
    }
}
