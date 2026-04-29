<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PartnerCreateRequest extends FormRequest
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
            'name'    => 'required|string|min:2|max:50',
            'mobile'  => 'required|digits:10',
            'address' => 'required|string|min:3|max:255',
            'type'      => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Ad mütləq qeyd olunmalıdır.',
            'name.min'         => 'Ad minimum 2 simvol olmalıdır.',
            'name.max'         => 'Ad maksimum 50 simvol ola bilər.',

            'mobile.required'  => 'Mobil nömrə mütləqdir.',
            'mobile.digits'    => 'Mobil nömrə 10 rəqəmli olmalıdır (0501234567).',

            'address.min'      => 'Ünvan minimum 3 simvol olmalıdır.',
            'address.max'      => 'Ünvan maksimum 255 simvol ola bilər.',
        ];
    }
}
