<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductCodeRequest extends FormRequest
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
            'code' => 'required',
            'warehouse_group_id' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'code.required'     => 'Vui lòng nhập mã hàng hóa!',
            'warehouse_group_id.required'     => 'Vui lòng chọn nhóm hàng hóa!',
        ];
	}
}
