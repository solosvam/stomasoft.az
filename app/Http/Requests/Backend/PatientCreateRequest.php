<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PatientCreateRequest extends FormRequest
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
            'surname' => 'required|string|min:2|max:50',
            'sex'     => 'required|in:0,1',
            'mobile'  => 'required|digits:10',
            'bday'    => 'required|date|before:today',
            'comment' => 'nullable|string|min:3|max:500',
            'address' => 'nullable|string|min:3|max:150',
            'fin'     => 'nullable|string|min:7|max:7',

        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Ad mütləq qeyd olunmalıdır.',
            'name.min'         => 'Ad minimum 2 simvol olmalıdır.',
            'name.max'         => 'Ad maksimum 50 simvol ola bilər.',

            'surname.required' => 'Soyad mütləq qeyd olunmalıdır.',
            'surname.min'      => 'Soyad minimum 2 simvol olmalıdır.',
            'surname.max'      => 'Soyad maksimum 50 simvol ola bilər.',

            'sex.required'     => 'Cinsiyyət seçilməlidir.',
            'sex.in'           => 'Cinsiyyət düzgün deyil.',

            'mobile.required'  => 'Mobil nömrə mütləqdir.',
            'mobile.digits'    => 'Mobil nömrə 10 rəqəmli olmalıdır (0501234567).',

            'bday.required'    => 'Doğum tarixi mütləqdir.',
            'bday.date'        => 'Doğum tarixi düzgün formatda deyil.',
            'bday.before'      => 'Doğum tarixi bugünkü tarixdən əvvəl olmalıdır.',

            'comment.min'      => 'Qeyd minimum 3 simvol olmalıdır.',
            'comment.max'      => 'Qeyd maksimum 500 simvol ola bilər.',

            'address.min'      => 'Ünvan minimum 3 simvol olmalıdır.',
            'address.max'      => 'Ünvan maksimum 150 simvol ola bilər.',

            'fin.*'      => 'Fin 7 simvol olmalıdır.',
        ];
    }
}
