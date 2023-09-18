<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankLoanRequest extends FormRequest
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
        $rules = [
            'lead' => 'required',
            'date' => 'required',
            'date_due' => 'required',
            'date_pay' => 'required',
            'amount_money' => 'required',
            'profit_amount' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'lead.required' => 'Vui lòng nhập nội dung vay!',
            'date.required' => 'Vui lòng nhập ngày vay!',
            'date_due.required' => 'Vui lòng chọn ngày đáo hạn!',
            'date_pay.required' => 'Vui lòng nhập ngày trả hàng tháng!',
            'amount_money.required' => 'Vui lòng nhập số tiền vay!',
            'profit_amount.required' => 'Vui lòng nhập tiền lãi!',
        ];
    }
}
