<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authService->loginWeb($request->validated());
            return redirect()->intended('/usuarios')->with('success', 'Bem-vindo ao Synapses GED!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logoutWeb();
        return redirect('/login')->with('success', 'Você saiu do sistema.');
    }
}
