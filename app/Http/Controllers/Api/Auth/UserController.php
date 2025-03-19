<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use App\Traits\ApiJsonResponceTrait;

class UserController extends Controller
{
    use ApiJsonResponceTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = User::paginate(20);

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();

        return $this->successResponse('User deleted successfully');
    }
}
