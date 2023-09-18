<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehousePlateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'code'     => 'required',
            'vat_lieu' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'code.required'     => 'Vui lòng nhập Mã hàng hoá!',
            'vat_lieu.required' => 'Vui lòng nhập Vật liệu!',
        ];
	}
}
