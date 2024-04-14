<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    public function authenticate($credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return Auth::user();
    }

    public function generateToken($user)
    {
        return $user->createToken('PrexTechnicalTest')->accessToken;
    }
}
