<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'cpf'      => 'nullable|string|max:14|unique:users,cpf,' . $userId,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'sometimes|required|in:admin,manager,user',
            'is_active'=> 'boolean'
        ];
    }
}
