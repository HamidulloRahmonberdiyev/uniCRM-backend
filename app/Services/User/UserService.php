<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct()
    {
        //
    }

    public function createUser(array $data)
    {
        $user = User::create([
            ''
        ]);
    }
}
