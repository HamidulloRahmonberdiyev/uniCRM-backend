<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        return [
            'access_token' => $user->createToken('access-token', ['*'], now()->addDays(7))->plainTextToken,
            'refresh_token' => $user->createToken('refresh-token', ['refresh'], now()->addDays(180))->plainTextToken
        ];
    }
}
