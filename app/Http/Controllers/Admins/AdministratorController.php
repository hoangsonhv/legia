<?php 
namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\Repositories\AdminRepository;
use App\Models\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdministratorController extends Controller {

	protected $adminRepository;
	protected $roleRepository;

	public $menu;

    function __construct(AdminRepository $adminRepository, RoleRepository $roleRepository)
    {
		$this->adminRepository = $adminRepository;
		$this->roleRepository  = $roleRepository;
		$this->menu            = [
			'root' => 'Quản trị viên',
			'data' => [
				'parent' => [
					'href'   => route('admin.administrator.index'),
					'label'  => 'Quản trị viên'
				]
			]
		];
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$breadcrumb                 = $this->menu;
		$breadcrumb['data']['list'] = ['label'  => 'Danh sách'];
		$titleForLayout             = $breadcrumb['data']['list']['label'];
		$limit                      = 10;
		$params                     = [];
		// search
		if($request->has('key_word')) {
			$params['key_word'] = $request->key_word;
		}

		$admins = $this->adminRepository->getAdmins($params, ['not_onwer' => true])->orderBy('id','DESC')->paginate($limit);
		$request->flash();
		return view('admins.administrators.index', compact('breadcrumb', 'titleForLayout', 'admins'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$breadcrumb                 = $this->menu;
		$breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
		$titleForLayout             = $breadcrumb['data']['list']['label'];
		$roles                      = $this->roleRepository->getRoles()->orderBy('id','DESC')->pluck('display_name', 'id');
		$positions                   = Admin::ARR_POSITION;

		return view('admins.administrators.create', compact('breadcrumb', 'titleForLayout', 'roles', 'positions'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(AdminRequest $request)
	{
		$input             = $request->all();
		$input['password'] = AdminHelper::hashPassword($input['username'], $input['password']);
		// check role
        if (empty($this->roleRepository->find($input['roles']))) {
            return redirect()->back()->withInput()->with('error', 'Phòng ban & quyền không tồn tại');
        }

        $admin = Admin::create($input);
        if ($admin) {
            // insert role
            $admin->roles()->attach($input['roles']);
            // update image
            $id = $admin->id;
            if ($request->hasFile('image')) {	//upload file
                $file = $request->file('image');
                $destinationPath = 'admin/uploads/admins/'.$id;
                $fileName = AdminHelper::uploadFile($file, $destinationPath);
            	if (!$fileName) {
            		return redirect()->route('admin.administrator.index')->with('error', 'Upload file thất bại!');
            	}
            	$updateFile = $admin->update(array('image' => $fileName));
            }
            if (empty($fileName) || (!empty($fileName) && $updateFile)) {
                if ($request->has('save-and-continue')) {
                    return redirect()->route('admin.administrator.create')->with('success', 'Đã tạo người quản trị thành công!');
                }
                return redirect()->route('admin.administrator.index')->with('success', 'Đã tạo người quản trị thành công!');
            }
        }
		return redirect()->route('admin.administrator.create')->with('error', 'Tạo người quản trị thất bại!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$admin = $this->adminRepository->getAdmins(['id' => $id], ['not_onwer' => true])->first();
		if ($admin) {
			$breadcrumb                 = $this->menu;
			$breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
			$titleForLayout             = $breadcrumb['data']['list']['label'];
			$roles                      = $this->roleRepository->getRoles()->orderBy('id','DESC')->pluck('display_name', 'id');
            $positions                   = Admin::ARR_POSITION;
			return view('admins.administrators.edit', compact('breadcrumb', 'titleForLayout', 'admin', 'roles',
                'positions'));
		}
		return redirect()->route('admin.administrator.index')->with('error', 'Người quản trị không tồn tại!');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(AdminRequest $request, $id)
	{
        $admin    = $this->adminRepository->getAdmins(['id' => $id], ['not_onwer' => true])->first();
        $input    = $request->except(['password']);
        $response = $this->updateAdmin($admin, $request, $id, $input);
		if (!empty($response['uploadValid'])) {
			return redirect()->route('admin.administrator.index')->with('error', 'Upload file thất bại!');
		} elseif (!empty($response['continueSuccess'])) {
			return redirect()->route('admin.administrator.edit', $id)->with('success', 'Đã cập nhật người quản trị thành công!');
		} elseif (!empty($response['exitSuccess'])) {
			return redirect()->route('admin.administrator.edit', $id)->with('success', 'Đã cập nhật người quản trị thành công!');
		} else {
			return redirect()->route('admin.administrator.edit', $id)->with('error', 'Cập nhật người quản trị thất bại!');
		}
	}

	public function meEdit()
	{
		$admin = Session::get('login');
		if ($admin) {
            $breadcrumb                 = $this->menu;
            $breadcrumb['data']['list'] = ['label'  => 'Cập nhật thông tin cá nhân'];
            $titleForLayout             = $breadcrumb['data']['list']['label'];
            $me                         = true;
            $positions                  = Admin::ARR_POSITION;
			return view('admins.administrators.edit', compact('breadcrumb', 'titleForLayout', 'admin', 'me', 'positions'));
		}
		return redirect()->route('admin.administrator.index')->with('error', 'Người quản trị không tồn tại!');
	}

	public function meUpdate(AdminRequest $request)
	{
        $admin    = Session::get('login');
        $id       = $admin->id;
        $input    = $request->except(['permission']);
        $response = $this->updateAdmin($admin, $request, $id);
		if (!empty($response['uploadValid'])) {
			return redirect()->route('admin.administrator.meEdit', $id)->with('error', 'Upload file thất bại!');
		} elseif (!empty($response['roleNotExist'])) {
			return redirect()->route('admin.administrator.meEdit', $id)->back()->withInput()->with('error', 'Phòng ban & quyền không tồn tại');
		} elseif (!empty($response['continueSuccess'])) {
			Session::put('password', $response['continueSuccess']);
			return redirect()->route('admin.administrator.meEdit', $id)->with('success', 'Đã cập nhật người quản trị thành công!');
		} elseif (!empty($response['exitSuccess'])) {
			Session::put('password', $response['exitSuccess']);
			return redirect()->route('admin.administrator.meEdit', $id)->with('success', 'Đã cập nhật người quản trị thành công!');
		} else {
			return redirect()->route('admin.administrator.meEdit', $id)->with('error', 'Cập nhật người quản trị thất bại!');
		}
	}

	private function updateAdmin($admin, $request, $id, $input=[]) {
        if (!$input) {
            $input = $request->all();
        }
		if ($admin) {
			if (!empty($input['password'])) {	//change password
				if (!empty($input['username'])) {
					$username = $input['username'];
				} else {
					$username = $admin->username;
				}
                $input['password'] = AdminHelper::hashPassword($username, $input['password']);
                $changePassword    = $input['password'];
        	} else {
        		unset($input['password']);
        	}
			if ($request->hasFile('image')) {	//upload file
	            $file = $request->file('image');
				if ($file->getClientOriginalExtension() != $admin->image) {	//upload file new
	                $destinationPath = 'admin/uploads/admins/'.$id;
	                $fileName = AdminHelper::uploadFile($file, $destinationPath);
					if (!$fileName) {
						return array('uploadValid'=>true);
					}
					$input['image'] = $fileName;
					// remove file
					if ($admin->image && file_exists(public_path().'/'.$destinationPath.'/'.$admin->image)) {
	                    unlink(public_path().'/'.$destinationPath.'/'.$admin->image);
	                }
				}
	        }
            // update admin
	        $update = $admin->update($input);
	        if ($update) {
	        	if (!empty($changePassword)) {
	        		$result = $changePassword;
	        	} else {
                    if (!strpos(Route::currentRouteName(), 'meUpdate')) {
                        // Delete role admin
                        $admin->roles()->detach();
                        // check role
                        if (empty($this->roleRepository->find($input['roles']))) {
                            return array('roleNotExist'=>$result);
                        }
                        // Insert role
                        $admin->roles()->attach($request->input('roles'));
                    }
	        		$result = true;
	        	}
                if ($request->has('save-and-continue')) {
                    return array('continueSuccess'=>$result);
                }
				return array('exitSuccess'=>$result);
			}
		}
		return array('noExists'=>true);
	}

	public function removeFile(Request $request) {
		$admin  = Session::get('login');
		$image  = $admin->image;
		$id     = $admin->id;
		$input  = array('image' => null);
		$update = $admin->update($input);
		if (!$update) {
			return response()->json(array('error' => 'Quá trình xóa hình ảnh gặp sự cố!'));
		}
		if (file_exists('admin/uploads/admins/'.$id.'/'.$image)) {
			unlink('admin/uploads/admins/'.$id.'/'.$image);
		}
		return response()->json(array('success' => 'Xóa hình ảnh thành công!'));
	}

    public function status($id)
    {
        $admin = $this->adminRepository->getAdminPermission($id);
        if ($admin) {
            $admin->update(array('status' => (1-$admin->status)));
            return redirect()->route('admin.administrator.index')->with('success', 'Cập nhật trạng thái người quản trị thành công!');
        }
        return redirect()->route('admin.administrator.index')->with('error', 'Người quản trị không tồn tại!');
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$admin = $this->adminRepository->getAdmins(['id' => $id], ['not_onwer' => true])->first();
		if (!$admin) {
			return redirect()->back()->with('error', 'Người quản trị không tồn tại!');
		}
		// $tmpAdmin = $admin;
		$delete = $admin->delete();
		if (!$delete) {
			return redirect()->back()->with('error', 'Xóa người quản trị thất bại!');
		}
		// remove file
        // $destinationPath = 'admin/uploads/admins/'.$id;
        // if ($tmpAdmin->image && file_exists(public_path().'/'.$destinationPath.'/'.$tmpAdmin->image)) {
        //     unlink(public_path().'/'.$destinationPath.'/'.$tmpAdmin->image);
        // }
		return redirect()->back()->with('success', 'Đã xóa người quản trị thành công!');
	}

	public function destroyMul(Request $request)
	{
		try {
			if ($request->has('delete_all')) {
				if (!$request->has('check-admin')) {
					throw new \Exception('Vui lòng chọn người quản trị muốn xóa!');
				}
				DB::beginTransaction();
				$listDelete = $request['check-admin'];
				foreach ($listDelete as $keyDel => $valDel) {
					$admin = $this->adminRepository->getAdminPermission($valDel);
					if (empty($admin)) {
						throw new \Exception('Người quản trị với mã '.$valDel.' không tồn tại!');
					}
					$tmpAdmin = $admin;
					$delete = $admin->delete();
					if (!$delete) {
						throw new \Exception('Xóa người quản trị với mã '.$valDel.' thất bại!');
					}
					// remove file
					$destinationPath = 'admin/uploads/admins/'.$valDel;
					if ($tmpAdmin->image && file_exists(public_path().'/'.$destinationPath.'/'.$tmpAdmin->image)) {
			            unlink(public_path().'/'.$destinationPath.'/'.$tmpAdmin->image);
			        }
				}
				DB::commit();
				return redirect()->back()->with('success', 'Đã xóa người quản trị thành công!');
			}
			return redirect()->back();
		} catch(\Exception $ex) {
			DB::rollback();
			report($ex);
			return redirect()->back()->with('error', $ex->getMessage());
		}
	}

	public function resetPassword($id)
	{
		$admin = $this->adminRepository->getAdmins(['id' => $id], ['not_onwer' => true])->first();
		if (!$admin) {
			return redirect()->back()->with('error', 'Người quản trị không tồn tại!');
		}
		$strRand  = Str::random(30);
		$password = AdminHelper::hashPassword($admin->username, $strRand);
		$update   = $admin->update( ['password' => $password] );
        if (!$update) {
        	return redirect()->back()->with('error', 'Tạo mật khẩu mới thất bại!');
        }
    	return redirect()->back()->with('success', 'Mật khẩu: <b>'.$strRand.'</b> đã được tạo thành công!');
	}
}
