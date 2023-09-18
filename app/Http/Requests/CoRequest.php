<?php

namespace App\Http\Requests;

use App\Helpers\DataHelper;
use Illuminate\Foundation\Http\FormRequest;

class CoRequest extends FormRequest
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
            'code'                   => 'required',
            'loai_vat_lieu'          => 'required',
            'do_day'                 => 'required',
            'tieu_chuan'             => 'required',
            'kich_co'                => 'required',
            'kich_thuoc'             => 'required',
            'chuan_bich'             => 'required',
            'chuan_gasket'           => 'required',
            'dv_tinh'                => 'required',
            'so_luong'               => 'required',
            'don_gia'                => 'required',
            'tong_gia'               => 'required',
            'vat'                    => 'required',
            'customer.code'          => 'required',
            'customer.ten'           => 'required',
        ];
    }

    public function messages()
	{
		return [
            'code.required'             => 'Vui lòng nhập Mã hàng hoá!',
            'loai_vat_lieu.required'    => 'Vui lòng nhập Loại vật liệu!',
            'do_day.required'           => 'Vui lòng nhập Độ dày!',
            'tieu_chuan.required'       => 'Vui lòng nhập Tiêu chuẩn!',
            'kich_co.required'          => 'Vui lòng nhập Kích cỡ!',
            'kich_thuoc.required'       => 'Vui lòng nhập Kích thước!',
            'chuan_bich.required'       => 'Vui lòng nhập Chuẩn bích!',
            'chuan_gasket.required'     => 'Vui lòng nhập Chuẩn gasket!',
            'dv_tinh.required'          => 'Vui lòng nhập Đơn vị tính!',
            'so_luong.required'         => 'Vui lòng nhập Số lượng!',
            'don_gia.required'          => 'Vui lòng nhập Đơn giá!',
            'tong_gia.required'         => 'Vui lòng nhập Tổng giá!',
            'vat.required'              => 'Vui lòng nhập Thuế VAT!',
            'customer.code.required'    => 'Vui lòng nhập Mã khách hàng!',
            'customer.ten.required'     => 'Vui lòng nhập Tên khách hàng!',
        ];
	}
}
