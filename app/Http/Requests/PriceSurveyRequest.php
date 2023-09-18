<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceSurveyRequest extends FormRequest
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
//            'code'      => 'required|unique:price_survey,code'.$id,
//            'type'   => 'required',
//            'core_customer_id' => 'required|numeric|min:2',
//            'product_group' => 'required',
//            'price' => 'required',
//        ];
//
//        if (isset($request['_method']) && $request['_method'] == 'POST') {
//            array_push($rules, ['code' => 'required|unique:price_survey,code']);
//        }

        return  [
            'co_id'      => 'required',
            'request_id'      => 'required',
            'type'   => 'required',
            'supplier' => 'required',
            'product_group' => 'required',
            'price' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'co_id.required'      => 'Vui lòng chọn CO!',
            'request_id.required'      => 'Vui lòng chọn Phiếu yêu cầu!',
            'code.required'      => 'Vui lòng nhập Mã yêu cầu!',
            'code.unique'      => 'Trùng Mã yêu cầu!',
            'type.required'   => 'Vui lòng chọn IMPO/DOME!',
            'supplier.required'   => 'Vui lòng nhập nhà cung cấp',
            'core_customer_id.required' => 'Vui lòng chọn mã khách hàng!',
            'core_customer_id.min' => 'Vui lòng chọn mã khách hàng!',
            'product_group.required' => 'Vui lòng nhập nhóm sản phẩm!',
            'price.required' => 'Vui lòng nhập giá trị báo giá!',
		];
	}
}
