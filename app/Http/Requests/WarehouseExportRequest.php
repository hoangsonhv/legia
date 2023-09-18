<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseExportRequest extends FormRequest
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
            'recipient_name' => 'required',
            'recipient_address' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'recipient_name.required'     => 'Vui lòng nhập tên người nhận hàng!',
            'recipient_address.required'     => 'Vui lòng nhập địa chỉ (bộ phận)!',
        ];
	}
}
