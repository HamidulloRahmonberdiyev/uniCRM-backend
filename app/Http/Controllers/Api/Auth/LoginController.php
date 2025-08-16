<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginPhoneRequest;
use App\Services\Auth\LoginService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(
        protected LoginService $loginService
    ) {}

    public function login(LoginPhoneRequest $request)
    {
        try {
            $result = $this->loginService->attemptLogin($request->phone, $request->password);

            return $this->successResponse($result, 'Login successful');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === Response::HTTP_TOO_MANY_REQUESTS
                ? Response::HTTP_TOO_MANY_REQUESTS
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return $this->errorResponse($e->getMessage(), $statusCode);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->loginService->logout($request->user());

            return $this->successResponse(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
