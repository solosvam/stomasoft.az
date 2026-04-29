<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CashierExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Məbləğ yazılmalıdır',
            'amount.numeric'  => 'Məbləğ rəqəm olmalıdır',
            'amount.min'      => 'Məbləğ 0-dan böyük olmalıdır',
        ];
    }
}
