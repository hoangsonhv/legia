<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufactureRequest extends FormRequest
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
        ];
    }

    public function messages()
	{
		return [
            'co_id.required'      => 'Vui lòng chọn mã CO!',
		];
	}
}
