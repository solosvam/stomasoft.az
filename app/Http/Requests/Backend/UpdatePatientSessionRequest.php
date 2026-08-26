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
            'location_id'           => ['required', 'array', 'min:1'],
            'location_id.*'         => ['required', 'integer', 'exists:service_locations,id'],

            'items'                 => ['required', 'array', 'min:1'],
            'items.*.service_id'    => ['required', 'integer', 'exists:services,id'],
            'items.*.price'         => ['required', 'numeric', 'min:0'],
            'items.*.percent'       => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.price_net'     => ['required', 'numeric', 'min:0'],
            'items.*.note'          => ['nullable', 'string', 'max:255'],

            'comment'               => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'location_id.required'      => 'Zona seç',
            'location_id.array'         => 'Zona seçimi yanlışdır',
            'location_id.min'           => 'Ən azı 1 zona seçilməlidir',
            'location_id.*.required'    => 'Zona seç',
            'location_id.*.integer'     => 'Zona seçimi yanlışdır',
            'location_id.*.exists'      => 'Seçilən zona tapılmadı',

            'items.required'            => 'Ən azı 1 xidmət olmalıdır',
            'items.min'                 => 'Ən azı 1 xidmət olmalıdır',
            'items.*.service_id.required' => 'Xidmət seç',
            'items.*.service_id.exists'   => 'Xidmət tapılmadı',

            'items.*.price.required'    => 'Qiymət yaz',
            'items.*.price.min'         => 'Qiymət 0-dan kiçik ola bilməz',

            'items.*.percent.required'  => 'Endirim yaz',
            'items.*.percent.min'       => 'Endirim 0-dan kiçik ola bilməz',
            'items.*.percent.max'       => 'Endirim 100-dən böyük ola bilməz',

            'items.*.price_net.required'=> 'Net qiymət yaz',
            'items.*.price_net.min'     => 'Net qiymət 0-dan kiçik ola bilməz',
        ];
    }
}
