<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseExportSellRequest extends FormRequest
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
//        $request = \Request::all();
//        $id = '';
//        if (\Request::has('id')) {
//            $id = ','.$request['id'];
//        }
//        $rules = [
//            'code'     => 'required|unique:warehouse_export_sell,code'.$id,
//            'core_customer_id' => 'required',
//            'buyer_name' => 'required',
//            'buyer_address' => 'required',
//            'buyer_phone' => 'required',
//            'buyer_tax_code' => 'required',
//        ];
//
//        if (isset($request['_method']) && $request['_method'] == 'POST') {
//            array_push($rules, ['code' => 'required|unique:warehouse_export_sell,code']);
//        }
//
//        return $rules;

        return [
            'core_customer_id' => 'required',
            'buyer_name' => 'required',
            'buyer_address' => 'required',
            'buyer_phone' => 'required',
            'buyer_tax_code' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'code.required'     => 'Vui lòng nhập Mã xuất kho bán hàng!',
            'core_customer_id.required' => 'Vui lòng chọn mã khách hàng!',
            'buyer_name.required' => 'Vui lòng nhập tên khách hàng!',
            'buyer_address.required' => 'Vui lòng nhập địa chỉ!',
            'buyer_phone.required' => 'Vui lòng nhập số điện thoại!',
            'buyer_tax_code.required' => 'Vui lòng nhập mã số thuế!',
        ];
	}
}
