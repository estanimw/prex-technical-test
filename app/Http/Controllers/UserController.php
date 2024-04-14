<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;

class UserController extends Controller
{
    protected $authenticationService;
    protected $userService;

    public function __construct(AuthenticationService $authenticationService, UserService $userService)
    {
        $this->authenticationService = $authenticationService;
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            $user = $this->authenticationService->authenticate($credentials);
            $token = $this->authenticationService->generateToken($user);

            return response()->json(['token' => $token], Response::HTTP_OK);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;

            $user = $this->userService->register($name, $email, $password);
            return response()->json(['user' => $user], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}