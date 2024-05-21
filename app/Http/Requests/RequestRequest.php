<?php

namespace App\Http\Requests;

use App\Helpers\DataHelper;
use Illuminate\Foundation\Http\FormRequest;

class   RequestRequest extends FormRequest
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
        $request = \Request::all();
        $category = [];
        if (!empty($request['is_request_payment'])) {
            return [];
        }
        if (!empty($request['category'])) {
            $categories = DataHelper::getCategoryPayment();
            foreach($categories as $kRoot => $vRoot) {
                foreach($vRoot['option'] as $key => $value) {
                    $category[] = $key;
                }
            }
        }
        return [
            'category' => 'required|in:'.implode(',', $category),
            'material' => 'required',
        ];
    }

    public function messages()
	{
		return [
            'category.required' => 'Vui lòng chọn Danh mục!',
            'category.in'       => 'Danh mục không tồn tại!',
            'material.required' => 'Vui lòng nhập Nội dung!',
        ];
	}
}
