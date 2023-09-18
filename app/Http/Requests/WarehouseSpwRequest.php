<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseSpwRequest extends FormRequest
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
        $valid = [
            'code'     => 'required',
            'vat_lieu' => 'required',
        ];
        if ($this->route('model') === 'thanhphamswg') {
            unset($valid['vat_lieu']);
        }
        return $valid;
    }

    public function messages()
	{
		return [
            'code.required'     => 'Vui lòng nhập Mã hàng hoá!',
            'vat_lieu.required' => 'Vui lòng nhập Vật liệu!',
        ];
	}
}
