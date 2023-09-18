<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'co_id'      => 'required',
            'core_customer_id'   => 'required',
            'recipient_name' => 'required',
            'recipient_phone' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'co_id.required'      => 'Vui lòng chọn mã CO!',
            'core_customer_id.required'   => 'Vui lòng chọn khách hàng!',
            'recipient_name.required' => 'Vui lòng nhập Họ tên người nhận!',
            'recipient_phone.required' => 'Vui lòng nhập SĐT người nhận!',
		];
	}
}
