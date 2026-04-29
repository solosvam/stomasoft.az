<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdate extends FormRequest
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
            'old_password'        => 'required|current_password',
            'new_password'        => 'required|string|min:6|max:20',
            'new_password_again'  => 'required|string|min:6|max:20|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Hazırki şifrə qeyd olunmalıdır.',
            'old_password.current_password' => 'Hazırki şifrə yanlışdır.',

            'new_password.required' => 'Yeni şifrə qeyd olunmalıdır.',
            'new_password.string'   => 'Yeni şifrə yalnız hərflər və rəqəmlərdən ibarət olmalıdır.',
            'new_password.min'      => 'Yeni şifrə ən azı 6 simvoldan ibarət olmalıdır.',
            'new_password.max'      => 'Yeni şifrə ən çox 20 simvoldan ibarət olmalıdır.',

            'new_password_again.required' => 'Yeni şifrə təkrarı qeyd olunmalıdır.',
            'new_password_again.string'   => 'Yeni şifrə təkrarı yalnız hərflər və rəqəmlərdən ibarət olmalıdır.',
            'new_password_again.min'      => 'Yeni şifrə təkrarı ən azı 6 simvoldan ibarət olmalıdır.',
            'new_password_again.max'      => 'Yeni şifrə təkrarı ən çox 20 simvoldan ibarət olmalıdır.',
            'new_password_again.same'     => 'Yeni şifrə təkrarı ilə yeni şifrə eyni olmalıdır.',
        ];
    }
}
