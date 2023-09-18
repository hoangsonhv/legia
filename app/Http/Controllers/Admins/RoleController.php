<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Repositories\RoleRepository;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    protected $roleRepository;

    public $menu;

    function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->menu = [
            'root' => 'Phòng ban & quyền',
            'data' => [
                'parent' => [
                    'href'   => route('admin.role.index'),
                    'label'  => 'Phòng ban & quyền'
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Danh sách'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $params                     = array();
        $limit                      = 10;
        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        $roles = $this->roleRepository->getRoles($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.roles.index',compact('breadcrumb', 'titleForLayout', 'roles'));
    }

    public function create()
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.roles.create',compact('breadcrumb', 'titleForLayout', 'permissions'));
    }

    public function store(RoleRequest $request)
    {
        $input = [
            'display_name' => $request->input('display_name'),
            'description'  => $request->input('description'),
            'permission'   => $request->input('permission'),
        ];
        $role  = Role::create($input);
        if ($role) {
            return redirect()->route('admin.role.index')->with('success','Tạo phòng ban & quyền thành công!');
        }
        return redirect()->route('admin.role.index')->with('error','Tạo phòng ban & quyền thất bại!');
    }

    public function edit($id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $role = $this->roleRepository->find($id);
        if ($role) {
            $permissions = config('permission.permissions');
            return view('admins.roles.edit',compact('breadcrumb', 'titleForLayout', 'role', 'permissions'));
        }
        return redirect()->route('admin.role.index')->with('error', 'Phòng ban & quyền không tồn tại!');
    }

    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleRepository->find($id);
        if ($role) {
            $role->display_name = $request->input('display_name');
            $role->description  = $request->input('description');
            $role->permission   = $request->input('permission');
            $role->save();
            return redirect()->route('admin.role.edit', ['id' => $id])->with('success','Cập nhật phòng ban & quyền thành công!');
        }
        return redirect()->back()->with('error', 'Phòng ban & quyền không tồn tại!');
    }

    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);
        if ($role) {
            $role->delete();
            return redirect()->route('admin.role.index')->with('success','Xóa phòng ban & quyền thành công!');
        }
        return redirect()->back()->with('error', 'Phòng ban & quyền không tồn tại!');
    }
}
