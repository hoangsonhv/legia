<?php 
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Route;

class AdminRequest extends Request {

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
        $id = '';
        if (\Request::has('id')) {
            $id = ','.$request['id'];
        }
        if (strpos(Route::currentRouteName(), 'admin.administrator.meUpdate') === false) {
			$validations = [
				'name'     => 'required',
				'username' => 'required|min:5|unique:admins,name'.$id,
				'password' => 'required|min:6',
				'mail'     => 'required|email|unique:admins,mail'.$id,
				'image'    => 'mimes:png,jpeg,gif',
				'roles'    => 'required'
			];
			if (\Request::has('id')) {
				unset($validations['password']);
			}
        } else {
        	$validations = [
				'name'     => 'required',
				'username' => 'required|min:5|unique:admins,name'.$id,
				'password' => 'required|min:6',
				'mail'     => 'required|email|unique:admins,mail'.$id,
				'image'    => 'mimes:png,jpeg,gif',
			];
			if(empty($request['password'])) {
				unset($validations['password']);
			}
        }
        return $validations;
	}

	public function messages()
	{
		return [
			'name.required'     => 'Vui lòng nhập Tên của bạn!',
			'username.required' => 'Vui lòng nhập Tài khoản!',
			'username.min'      => 'Vui lòng nhập Tài khoản tối thiểu 5 kí tự!',
			'username.unique'   => 'Tài khoản của bạn đã tồn tại!',
			'password.required' => 'Vui lòng nhập Mật khẩu!',
			'password.min'      => 'Vui lòng nhập Mật khẩu tối thiểu 6 kí tự!',
			'mail.required'     => 'Vui lòng nhập Email!',
			'mail.email'        => 'Email không đúng định dạng!',
			'mail.unique'       => 'Email của bạn đã tồn tại!',
			'image.mimes'       => 'Chỉ được upload hình ảnh .png, .jpg, .gif!',
			'roles.required'    => 'Vui lòng chọn phòng ban & quyền!',
		];
	}

}
