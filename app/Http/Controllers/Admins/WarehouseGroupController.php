<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseExportRequest;
use App\Http\Requests\WarehouseGroupRequest;
use App\Http\Requests\WarehouseReceiptRequest;
use App\Models\Admin;
use App\Models\CoStepHistory;
use App\Models\Repositories\WarehouseGroupRepository;
use App\Models\WarehouseGroup;
use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\WarehouseReceiptRepository;
use App\Models\Repositories\CoRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Repositories\WarehouseExportRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\WarehouseHistoryRepository;

class WarehouseGroupController extends Controller
{
    /**
     * @var
     */
    protected $warehouseGroupRepo;

    /**
     * @var array
     */
    public $menu;

    function __construct(WarehouseGroupRepository $warehouseGroupRepo)
    {
        $this->warehouseGroupRepo = $warehouseGroupRepo;
        $this->menu = [
            'root' => 'Quản lý nhóm',
            'data' => [
                'parent' => [
                    'href' => route('admin.warehouse-export.index'),
                    'label' => 'Nhóm'
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Danh sách'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $params = array();
        $limit = 10;

        // search
        if ($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        $datas = $this->warehouseGroupRepo->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_group.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        return view('admins.warehouse_group.create', compact('breadcrumb', 'titleForLayout',
            'permissions'));
    }

    public function store(WarehouseGroupRequest $request)
    {
        $inputs = $request->input();
        if(isset($inputs['warehouse_product']) && is_array($inputs['warehouse_product'])) {
            $inputs['warehouse_product'] = implode(',', array_keys($inputs['warehouse_product']));
        }
        if(isset($inputs['warehouse_ingredient']) && is_array($inputs['warehouse_ingredient'])) {
            $inputs['warehouse_ingredient'] = implode(',', array_keys($inputs['warehouse_ingredient']));
        }
        try {
            \DB::beginTransaction();
            $inputs['admin_id'] = Session::get('login')->id;
            $model = $this->warehouseGroupRepo->insert($inputs);
            \DB::commit();
            return redirect()->route('admin.warehouse-group.index')->with('success', 'Tạo nhóm hàng hóa thành công!');
        } catch (\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.warehouse-export.index')->with('error', 'Tạo nhóm hàng hóa thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $model = $this->warehouseGroupRepo->find($id);
        if ($model) {
            $permissions = config('permission.permissions');
            return view('admins.warehouse_group.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions'));
        }
        return redirect()->route('admin.warehouse-group.index')->with('error', 'Nhóm hàng hóa không tồn tại!');
    }

    public function update(WarehouseGroupRequest $request, $id)
    {
        $model = $this->warehouseGroupRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            if(isset($inputs['warehouse_product']) && is_array($inputs['warehouse_product'])) {
                $inputs['warehouse_product'] = implode(',', array_keys($inputs['warehouse_product']));
            }
            if(isset($inputs['warehouse_ingredient']) && is_array($inputs['warehouse_ingredient'])) {
                $inputs['warehouse_ingredient'] = implode(',', array_keys($inputs['warehouse_ingredient']));
            }
//            try {
                \DB::beginTransaction();
                // Save request
                $model = $this->warehouseGroupRepo->update($inputs, $id);
                \DB::commit();
                return redirect()->route('admin.warehouse-group.edit', ['id' => $id])->with('success', 'Cập nhật Phiếu xuất kho thành công!');
//            } catch (\Exception $ex) {
//                \DB::rollback();
//                report($ex);
//            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật nhóm hàng hóa không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Nhóm hàng hóa không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->warehouseGroupRepo->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.warehouse-group.index')->with('success', 'Xóa nhóm hàng hóa thành công!');
        }
        return redirect()->back()->with('error', 'Nhóm hàng hóa không tồn tại!');
    }
}