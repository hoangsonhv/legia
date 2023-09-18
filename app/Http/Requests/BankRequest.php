<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'name_bank'      => 'required',
            'account_name'   => 'required',
//            'account_number' => 'required'
        ];

        $request = \Request::all();
        if (isset($request['_method']) && $request['_method'] == 'POST') {
            array_push($rules, ['account_balance' => 'required|max:11']);
        }

        return $rules;
    }

    public function messages()
	{
		return [
            'name_bank.required'      => 'Vui lòng nhập Tên ngân hàng!',
            'account_name.required'   => 'Vui lòng nhập Tên tài khoản!',
            'account_number.required' => 'Vui lòng chọn Số tài khoản!',
            'account_balance.required' => 'Vui lòng nhập Số dư tài khoản!',
            'account_balance.max' => 'Số dư tài khoản quá lớn!',
		];
	}
}
