<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): View
    {
        $usuarios = $this->userService->listar($request->all());
        return view('usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('usuarios.form');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->criar($request->validated());
        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(int $id): View
    {
        $usuario = $this->userService->buscarPorId($id);
        return view('usuarios.form', compact('usuario'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $this->userService->atualizar($id, $request->validated());
        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->userService->deletar($id);
        return redirect()->route('usuarios.index')->with('success', 'Usuário removido.');
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $this->userService->toggleStatus($id);
        return back()->with('success', 'Status alterado.');
    }
}
