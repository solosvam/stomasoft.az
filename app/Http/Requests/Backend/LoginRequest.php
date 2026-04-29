<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'login'     => 'required|exists:user,login',
            'password'  => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'login.required'    => 'Login qeyd olunmalıdır.',
            'login.exists'      => 'Login səhfdir.',
            'password.required' => 'Şifrə qeyd olunmalıdır və minimum 6 simvoldan ibarət olmalıdır.',
            'password.min'      => 'Şifrə minimum 6 simvoldan ibarət olmalıdır.'
        ];
    }

    public function checkAccount($user)
    {
        if (!$user->getAttribute('active')){
            throw ValidationException::withMessages([
                'login' => trans('Hesab aktiv deyil'),
            ]);
        }
    }
}
