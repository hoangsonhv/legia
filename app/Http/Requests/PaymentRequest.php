<?php

namespace App\Http\Requests;

use App\Helpers\DataHelper;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'name_receiver' => 'required',
            'money_total'   => 'required|integer|min:1',
            'payment_method' => 'required'
        ];
    }

    public function messages()
	{
		return [
            'name_receiver.required' => 'Vui lòng nhập Người nhận tiền!',
            'money_total.required'   => 'Vui lòng nhập Số tiền chi!',
            'payment_method.required'=> 'Vui lòng chọn ngân hàng!',
            'money_total.integer'    => 'Vui lòng nhập Số tiền chi là số!',
            'money_total.min'        => 'Vui lòng nhập Số tiền tối thiểu là 1!',
        ];
	}
}
