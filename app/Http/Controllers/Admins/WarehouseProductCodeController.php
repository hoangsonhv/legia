<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseExportRequest;
use App\Http\Requests\WarehouseProductCodeRequest;
use App\Models\Admin;
use App\Models\CoStepHistory;
use App\Models\WarehouseGroup;
use App\Models\WarehouseHistory;
use App\Models\WarehouseProductCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Repositories\WarehouseProductCodeRepository;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseProductCodeController extends Controller
{
    protected $warehouseProductCodeRepository;
    /**
     * @var array
     */
    public $menu;

    function __construct(WarehouseProductCodeRepository $warehouseProductCodeRepository)
    {
        $this->warehouseProductCodeRepository = $warehouseProductCodeRepository;
        $this->menu = [
            'root' => 'Quản lý mã hàng hóa',
            'data' => [
                'parent' => [
                    'href' => route('admin.warehouse-export.index'),
                    'label' => 'Mã hàng hóa'
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

        $datas = $this->warehouseProductCodeRepository->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_product_code.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $groups = WarehouseGroup::all()->pluck('name', 'id')->toArray();

        return view('admins.warehouse_product_code.create', compact('breadcrumb', 'titleForLayout',
            'permissions', 'groups'));
    }

    public function store(WarehouseProductCodeRequest $request)
    {
        $input = $request->input();
        try {
            \DB::beginTransaction();
            $input['admin_id'] = Session::get('login')->id;
            $model = $this->warehouseProductCodeRepository->insert($input);
            \DB::commit();
            return redirect()->route('admin.warehouse-product-code.index')->with('success', 'Tạo mã hàng hóa thành công!');
        } catch (\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.warehouse-product-code.index')->with('error', 'Tạo mã hàng hóa thất bại!');
    }

    public function edit($id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];

        $model = $this->warehouseProductCodeRepository->find($id);
        if ($model) {
            $permissions = config('permission.permissions');
            $groups = WarehouseGroup::all()->pluck('name', 'id')->toArray();
            return view('admins.warehouse_product_code.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'groups'));
        }
        return redirect()->route('admin.warehouse_product_code.index')->with('error', 'Mã hàng hóa không tồn tại!');
    }

    public function update(WarehouseProductCodeRequest $request, $id)
    {
        $model = $this->warehouseProductCodeRepository->find($id);
        if ($model) {
            $inputs = $request->input();
            try {
                \DB::beginTransaction();
                $model = $this->warehouseProductCodeRepository->update($inputs, $id);
                \DB::commit();
                return redirect()->route('admin.warehouse-product-code.edit', ['id' => $id])->with('success', 'Cập nhật mã hàng hóa thành công!');
            } catch (\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật mã hàng hóa không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Mã hàng hóa không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->warehouseProductCodeRepository->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.warehouse-product-code.index')->with('success', 'Xóa mã hàng hóa thành công!');
        }
        return redirect()->back()->with('error', 'Mã hàng hóa không tồn tại!');
    }

    public function importCode(Request $request)
    {
        $user = Session::get('login');
        if ($request->has('file')) {
            $aContent = Excel::toArray([], $request->file('file'));
            if (!empty($aContent[0])) {
                $allData    = $aContent[0];
                foreach($allData as $key => $val) {
                    $group = WarehouseGroup::where('code', trim($val[2]))->first();
                    if($key > 0) {
                        if(!$val[0]) {
                            continue;
                        }
                        $matchThese = [
                            'code' => $val[0],
                            'name' => $val[1],
//                            'warehouse_group_id' => $group ? $group->id : 0,
                        ];
                        $dataUpdate = array_merge($matchThese, [
                            'admin_id' => $user->id,
                            'warehouse_group_id' => $group ? $group->id : 0,
                        ]);
                        WarehouseProductCode::updateOrCreate($matchThese, $dataUpdate);
                    }
                }
            }
        }
        return redirect()->route('admin.warehouse-product-code.index')
            ->with('success', 'Import mã hàng hóa thành công!');
    }
}