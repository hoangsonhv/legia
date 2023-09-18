<?php

namespace App\Http\Requests;

use App\Helpers\DataHelper;
use Illuminate\Foundation\Http\FormRequest;

class ReceiptRequest extends FormRequest
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
            'payment_method' => 'required',
            'name_receiver'  => 'required',
            'money_total'    => 'required|integer|min:1',
        ];
    }

    public function messages()
	{
		return [
            'payment_method.required' => 'Vui lòng chọn Phương thức thanh toán!',
            'name_receiver.required'  => 'Vui lòng nhập Người nhận tiền!',
            'money_total.required'    => 'Vui lòng nhập Tổng số tiền thu!',
            'money_total.integer'     => 'Vui lòng nhập Tổng số tiền thu là số!',
            'money_total.min'         => 'Vui lòng nhập Tổng số tiền thu tối thiểu là 1!',
        ];
	}
}
