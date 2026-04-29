<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tooth_id'           => ['required', 'array', 'min:1'],
            'tooth_id.*'         => ['required', 'integer', 'min:1'],

            'items'              => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
            'items.*.percent'    => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.price_net'  => ['required', 'numeric', 'min:0'],
            'items.*.note'       => ['nullable', 'string', 'max:255'],

            'comment'            => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tooth_id.required'           => 'Diş seç',
            'tooth_id.array'              => 'Diş seç',
            'tooth_id.min'                => 'Ən azı 1 diş seçilməlidir',
            'tooth_id.*.required'         => 'Diş seç',
            'tooth_id.*.integer'          => 'Diş seçimi yanlışdır',
            'tooth_id.*.min'              => 'Diş seçimi yanlışdır',

            'items.required'              => 'Ən azı 1 xidmət olmalıdır',
            'items.min'                   => 'Ən azı 1 xidmət olmalıdır',
            'items.*.service_id.required' => 'Xidmət seç',
            'items.*.service_id.exists'   => 'Xidmət tapılmadı',
            'items.*.price.required'      => 'Qiymət yaz',
            'items.*.price.min'           => 'Qiymət 0-dan kiçik ola bilməz',
            'items.*.percent.required'    => 'Endirim yaz',
            'items.*.percent.min'         => 'Endirim 0-dan kiçik ola bilməz',
            'items.*.percent.max'         => 'Endirim 100-dən böyük ola bilməz',
            'items.*.price_net.required'  => 'Net qiymət yaz',
            'items.*.price_net.min'       => 'Net qiymət 0-dan kiçik ola bilməz',
        ];
    }
}
