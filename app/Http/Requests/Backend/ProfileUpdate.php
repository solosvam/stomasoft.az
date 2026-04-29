<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdate extends FormRequest
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
            'name'      => 'required|string|min:3|max:20',
            'surname'   => 'required|string|min:3|max:20',
            'mobile'    => 'required|regex:/^[0-9]{12}$/|unique:user,mobile,' . $this->user()->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'Ad qeyd olunmalıdır.',
            'name.min'          => 'Ad minimum 3 simvoldan ibarət olmalıdır.',
            'name.max'          => 'Ad maksimum 20 simvoldan ibarət olmalıdır.',

            'surname.required'  => 'Soyad qeyd olunmalıdır.',
            'surname.min'       => 'Soyad minimum 3 simvoldan ibarət olmalıdır.',
            'surname.max'       => 'Soyad maksimum 20 simvoldan ibarət olmalıdır.',

            'mobile.required'   => 'Mobil nömrə qeyd olunmalıdır.',
            'mobile.regex'      => 'Mobil nömrə 12 rəqəmdən ibarət olmalıdır. Misal: 994501234567',
            'mobile.unique'     => 'Bu nömrə artıq qeydiyyatdan keçib.',
        ];
    }
}
