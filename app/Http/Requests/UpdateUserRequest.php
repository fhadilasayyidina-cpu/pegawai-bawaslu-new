<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $targetUser = $this->route('users');
        $user = Auth::user();
        
        if (! $user) {
            return false;
        }

        if ($user->role == Role::ADMIN || $user->id == $targetUser->id || $user->role == Role::OPERATOR) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $targetUser = $this->route('user');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$targetUser->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,staf,pimpinan',
        ];
    }
}
