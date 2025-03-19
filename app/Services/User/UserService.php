<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function __construct()
    {
        //
    }

    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'password' => Hash::make($data['password']),
                'phone' => sanitizePhone($data['phone']),
                'username' => $data['username'],
            ]);

            if (!empty($data['roles'])) {
                $roles = Role::whereIn('id', $data['roles'])->get();
                $user->roles()->sync($roles);
            }

            return $user;
        });
    }

    public function updateUser($user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {

            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'phone' => isset($data['phone']) ? sanitizePhone($data['phone']) : $user->phone,
                'username' => $data['username'] ?? $user->username,
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            if (!empty($data['roles'])) {
                $roles = Role::whereIn('id', $data['roles'])->get();
                $user->roles()->sync($roles);
            }

            return $user;
        });
    }
}
