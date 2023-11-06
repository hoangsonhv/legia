<?php 
namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckLoginAdminRequest;
use App\Models\Admin;
use App\Models\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller {

	protected $adminRepository;

    function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

	public function getLogin(Request $request)
    {
		if(Session::has('login')) {
			if (Session::has('last_url')) {
				return redirect(Session::get('last_url'));
			} else {
				return redirect()->route('admin.dashboard.quotation');
			}
		}
    	return view('admins.login');
    }

    public function postLogin(CheckLoginAdminRequest $request)
    {
		$where['username'] = $request->username;
		$where['password'] = AdminHelper::hashPassword($request->username, $request->password);
		$where['status']   = 1;
    	$admin = $this->adminRepository->getAdmins($where)->get()->first();
    	if(!empty($admin)) {
    		if($request->has('remember')) { //set cookie
				setcookie('remember-user', $request->username, time() + 7*24*60*60);
				setcookie('remember-pass', base64_encode(base64_encode($request->password)), time() + 7*24*60*60);
			} elseif (isset($_COOKIE['remember-user']) && isset($_COOKIE['remember-pass'])) {
				setcookie('remember-user', "", time() - 3600);
				setcookie('remember-pass', "", time() - 3600);
			}
			//save session
			Session::put('login', $admin);
			//upate lasttime
			$admin->update(array('lasttime' => date('Y-m-d H:i:s')));
			//redirect
			if (Session::has('last_url')) {
				return redirect(Session::get('last_url'));
			} else {
				return redirect()->route('admin.dashboard.quotation');
			}
    	}
    	return redirect()->route('admin.login.getLogin')->with('error', 'Thông tin tài khoản không tồn tại!');
    }

	public function getLogout() 
	{
		Session::flush();
		return redirect()->route('admin.login.getLogin');
	}

	public function getReset()
    {
		if(Session::has('login')) {
			if (Session::has('last_url')) {
				return redirect(Session::get('last_url'));
			} else {
				return redirect()->route('admin.dashboard.index');
			}
		}
    	return view('admins.reset');
    }

    public function postReset(Request $request)
    {
    	$messages = array(
			'username.required' => 'Vui lòng nhập Tài khoản của bạn!',
			'mail.required'     => 'Vui lòng nhập Thư điện tử của bạn!',
			'mail.email'        => 'Thư điện tử không đúng định dạng!',
    	);
    	$this->validate($request, [
			'username' => 'required',
			'mail'     => 'required|email',
    	], $messages);
		
		$admin = Admin::where('username', '!=', 'admin')
				->where('username', $request->username)
				->where('mail', $request->mail);
		if ($admin) {
			$passwordSend = date('YmdHis');
			$password = AdminHelper::hashPassword($request->username, date('YmdHis'));
			$update = $admin->update(array('password' => $password));
			if ($update) {
				$mail = AdminHelper::sendMail($request->mail, 'Lấy lại mật khẩu', 'Mật khẩu mới của bạn là: '.$passwordSend);
				if (!$mail) {
					return redirect()->back()->with('error', 'Quá trình gửi mail gặp sự cố! Vui lòng thử lại!');
				}
				return redirect()->back()->with('success', 'Vui lòng kiểm tra thư điện tử của bạn!');
			}
		}
		return redirect()->back()->with('error', 'Thông tin nhập vào không chính xác!');
    }
}
