<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class AddPatientServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tooth_id'      => 'required|array|min:1',
            'tooth_id.*'    => 'required|integer|min:1',

            'service_id'    => 'required|array|min:1',
            'service_id.*'  => 'required|integer|exists:services,id',

            'price'         => 'required|array|min:1',
            'price.*'       => 'required|numeric|min:0',

            'percent'       => 'required|array|min:1',
            'percent.*'     => 'required|numeric|min:0|max:100',

            'price_net'     => 'required|array|min:1',
            'price_net.*'   => 'required|numeric|min:0',

            'note'          => 'nullable|array',
            'note.*'        => 'nullable|string|max:255',
            'comment'       => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'tooth_id.required'       => 'Diş seç',
            'tooth_id.array'          => 'Diş seçimi yanlışdır',
            'tooth_id.*.required'     => 'Diş seç',
            'tooth_id.*.integer'      => 'Diş seçimi yanlışdır',
            'tooth_id.*.min'          => 'Diş seçimi yanlışdır',

            'service_id.*.required'   => 'Xidmət seç',
            'service_id.*.exists'     => 'Xidmət tapılmadı',

            'price.*.required'        => 'Qiymət yaz',
            'price.*.min'             => 'Qiymət 0-dan böyük olmalıdır',

            'percent.*.required'      => 'Endirim faizi yaz',
            'percent.*.min'           => 'Endirim faizi mənfi ola bilməz',
            'percent.*.max'           => 'Endirim faizi 100-dən böyük ola bilməz',

            'price_net.*.required'    => 'Net qiymət yaz',
            'price_net.*.min'         => 'Net qiymət 0-dan böyük olmalıdır',
        ];
    }
}
