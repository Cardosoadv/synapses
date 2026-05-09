<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(array $credentials)
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new Exception("Credenciais inválidas.");
        }

        $user = auth('api')->user();
        
        if (!$user->is_active) {
            auth('api')->logout();
            throw new Exception("Sua conta está inativa. Entre em contato com o administrador.");
        }

        $user->update(['last_login_at' => now()]);

        return $this->respondWithToken($token);
    }

    public function loginWeb(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw new Exception("E-mail ou senha incorretos.");
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            throw new Exception("Sua conta está inativa.");
        }

        $user->update(['last_login_at' => now()]);

        return true;
    }

    public function logout()
    {
        auth('api')->logout();
    }

    public function logoutWeb()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function me()
    {
        return auth('api')->user();
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ];
    }
}
