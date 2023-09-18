<?php

namespace App\Http\Requests;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class BankHistoryTransactionRequest extends FormRequest
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
            'type'               => 'required|in:'.implode(',', array_keys(TransactionType::all())),
            'transaction_amount' => 'required|integer|min:1',
        ];
    }

    public function messages()
	{
		return [
            'type.required'               => 'Vui lòng chọn loại giao dịch!',
            'type.in'                     => 'Vui lòng chỉ chọn loại giao dịch Nạp / Rút!',
            'transaction_amount.required' => 'Vui lòng nhập Số tiền giao dịch!',
            'transaction_amount.integer'  => 'Vui lòng nhập Số tiền giao dịch là số!',
            'transaction_amount.min'      => 'Vui lòng nhập Số tiền tối thiểu là 1!',
		];
	}
}
