<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Access\ApiAuthServiceInterface;

class AuthController extends Controller
{
    protected ApiAuthServiceInterface $authService;

    public function __construct(ApiAuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $validatedRequest = $request->validated();
        $this->authService->register(
            $validatedRequest['firstName'],
            $validatedRequest['lastName'],
            $validatedRequest['email'],
            $validatedRequest['password']
        );
    }

    public function login(LoginRequest $request)
    {
        $validatedRequest = $request->validated();
        $token = $this->authService->login(
            $validatedRequest['email'],
            $validatedRequest['password']
        );
        return $token
            ? response(['token' => $token], 200)
            : response([
                'message' => 'Auth failed',
                'errors' => ['Unknown username/password combination']
            ], 422);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
    }
}
