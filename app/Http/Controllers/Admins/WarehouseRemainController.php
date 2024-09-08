<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRemainRequest;
use App\Imports\Remains\WarehouseRemainsImport;
use App\Imports\Warehouse\WarehouseImport;
use App\Models\Repositories\WarehouseRemainRepository;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use \Log;

class WarehouseRemainController extends Controller
{
    protected $warehouseRemainRepository;
    protected $warehouseService;
    public $menu;

    function __construct(
        WarehouseRemainRepository $warehouseRemainRepository,
        WarehouseService $warehouseService
    ) {
        $this->warehouseRemainRepository = $warehouseRemainRepository;
        $this->warehouseService = $warehouseService;
        $this->menu                     = [
            'root' => 'Quản lý Kho còn lại',
            'data' => [
                'parent' => [
                    'href'   => route('admin.warehouse-remain.index'),
                    'label'  => 'Quản lý Kho Tấm'
                ]
            ]
        ];
    }

    public function index(Request $request, $model=null)
    {
        if (!$model) {
            $model = 'ccdc';
        }
        $nameWarehouse = $this->checkExistModel($model);
        $types         = DataHelper::getModelWarehouses('remain');

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Danh sách ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $params                     = $request->all();
        $limit                      = 10;
        $warehouseRemains = $this->warehouseService->search($model, $params);
        $request->flash();
        return view('admins.warehouse_remains.index',compact('types', 'breadcrumb', 'titleForLayout', 'warehouseRemains', 'model'));
    }

    public function create($model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.warehouse_remains.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'model'));
    }

    public function store(WarehouseRemainRequest $request, $model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $input = $request->except('_token');
        if($request->ajax()){
            return $this->warehouseService->storeOrUpdate($model, $input, false);
        }
        if ($this->warehouseService->storeOrUpdate($model, $input)) {
            return redirect()->route('admin.warehouse-remain.index', ['model' => $model])->with('success','Tạo Vật Liệu thành công!');
        }
        return redirect()->route('admin.warehouse-remain.index', ['model' => $model])->with('error','Tạo Vật Liệu thất bại!');
    }

    public function edit(Request $request, $model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];

        if ($warehouseRemain = $this->warehouseService->edit($id,$model)) {
            $permissions = config('permission.permissions');
            return view('admins.warehouse_remains.edit',compact('breadcrumb', 'titleForLayout', 'warehouseRemain', 'permissions', 'model'));
        }
        return redirect()->route('admin.warehouse-remain.index', ['model' => $model])->with('error', 'Vật Liệu không tồn tại!');
    }

    public function update(WarehouseRemainRequest $request, $model, $id)
    {
        $nameWarehouse   = $this->checkExistModel($model);

        $input           = $request->except('_token', '_method');
        $warehouseRemain = $this->warehouseRemainRepository->find($model, $id);
        $input['l_id'] = $id;
        if ($this->warehouseService->storeOrUpdate($model,$input)) {
            // $this->warehouseRemainRepository->update($model, $input, $warehouseRemain);
            return redirect()->route('admin.warehouse-remain.edit', ['model' => $model, 'id' => $id])->with('success','Cập nhật Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function destroy($model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);
        if ($this->warehouseService->delete($id,$model)) {
            return redirect()->route('admin.warehouse-remain.index', ['model' => $model])->with('success','Xóa Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function import(Request $request, $model) {
        $nameWarehouse = $this->checkExistModel($model);

        try{
            set_time_limit(0);
            $file = $request->file('import-remain');
            $ext = DataHelper::getExtensionImport($file->extension());
            if ($ext) {
                \DB::beginTransaction();
                Excel::import(new WarehouseImport($model), $file);
                \DB::commit();
                return redirect()->back()->with('success','Đã import dữ liệu thành công.');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $ex) {
            Log::error('--- Lỗi import: '.$nameWarehouse.' ---');
            $errorMessages = [];
            $failures      = $ex->failures();
            foreach ($failures as $failure) {
                foreach($failure->errors() as $error) {
                    $errorMessages[] = 'Có một lỗi xảy ra trên dòng số '.$failure->row().'. '.$error;
                }
            }
            Session::flash('errors', $errorMessages);
            Log::error($ex->errors());
            Log::error('--- End: Lỗi import: '.$nameWarehouse.'  ---');
            \DB::rollback();
            return redirect()->back();
        } catch(\Exception $ex) {
            Log::error('--- Lỗi import: '.$nameWarehouse.'  ---');
            Log::error($ex);
            Log::error('--- End: Lỗi import: '.$nameWarehouse.'  ---');
            \DB::rollback();
        }
        return redirect()->back()->with('error','Quá trình import dữ liệu thất bại.');
    }

    private function checkExistModel($data) {
        $typeModels = DataHelper::getModelWarehouses('remain');
        if (!array_key_exists($data, $typeModels)) {
            dd('Thông tin không hợp lệ.');
        }
        return $typeModels[$data];
    }
}
