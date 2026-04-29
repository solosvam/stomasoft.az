<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PatientPayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mode'      => ['required', 'in:doctor,total'],
            'doctor_id' => ['nullable', 'integer'],
            'amount'    => ['required', 'numeric', 'min:0.01'],
            'method'    => ['required', 'in:cash,pos,c2c'],
            'note'      => ['nullable', 'string', 'max:255'],
        ];
    }
}
