<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required|string|min:3|max:20',
            'surname'      => 'required|string|min:3|max:20',
            'mobile'       => 'required|regex:/^[0-9]{12}$/',
            'login'        => 'required|unique:user,login',
            'password'     => 'required|string|min:6|max:20',
            'account_type' => 'required|in:doctor,technician',
            'role_name'    => 'required|exists:roles,name',
            'parent_id'    => 'nullable|exists:user,id',
            'specialty_id' => 'required_if:account_type,doctor|nullable|exists:specialties,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => 'Ad qeyd olunmalıdır.',
            'name.min'              => 'Ad minimum 3 simvoldan ibarət olmalıdır.',
            'name.max'              => 'Ad maksimum 20 simvoldan ibarət olmalıdır.',
            'surname.required'      => 'Soyad qeyd olunmalıdır.',
            'surname.min'           => 'Soyad minimum 3 simvoldan ibarət olmalıdır.',
            'surname.max'           => 'Soyad maksimum 20 simvoldan ibarət olmalıdır.',
            'mobile.required'       => 'Mobil nömrə qeyd olunmalıdır.',
            'mobile.regex'          => 'Mobil nömrə 12 rəqəmdən ibarət olmalıdır. Misal: 994501234567',
            'login.required'        => 'Login qeyd olunmalıdır.',
            'login.unique'          => 'Bu login artıq qeydiyyatdan keçib. Zəhmət olmasa, başqa bir login istifadə edin.',
            'password.required'     => 'Şifrə qeyd olunmalıdır.',
            'password.min'          => 'Şifrə minimum 6 simvoldan ibarət olmalıdır.',
            'password.max'          => 'Şifrə maksimum 20 simvoldan ibarət olmalıdır.',
            'account_type.required' => 'Hesab tipi seçilməlidir.',
            'account_type.in'       => 'Hesab tipi düzgün seçilməyib.',
            'role_name.required'    => 'Rol seçilməlidir.',
            'specialty_id.required_if' => 'Həkim üçün ixtisas seçilməlidir.',
        ];
    }
}
