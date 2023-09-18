<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'code'      => 'required',
            'name'   => 'required',
            'tax_code' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'code.required'      => 'Vui lòng nhập Mã khách hàng!',
            'name.required'   => 'Vui lòng nhập Tên khách hàng!',
            'tax_code.required' => 'Vui lòng chọn Mã số thuế!',
            'address.required' => 'Vui lòng chọn Địa chỉ!',
            'phone.required' => 'Vui lòng chọn Số điện thoại!',
		];
	}
}
