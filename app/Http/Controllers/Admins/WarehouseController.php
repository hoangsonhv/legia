<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\DataHelper;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehousePlateRequest;
use App\Imports\Plates\WarehousePlatesImport;
use App\Imports\Warehouse\WarehouseImport;
use App\Models\Repositories\WarehousePlateRepository;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use \Log;

class WarehouseController extends Controller
{
    protected $warehousePlateRepository;
    protected $warehouseService;
    public $menu;

    function __construct(
        WarehousePlateRepository $warehousePlateRepository,
        WarehouseService $warehouseService
    ) {
        $this->warehousePlateRepository = $warehousePlateRepository;
        $this->warehouseService = $warehouseService;
        $this->menu                     = [
            'root' => 'Quản lý Kho',
            'data' => [
                'parent' => [
                    'href'   => route('admin.warehouse-plate.index'),
                    'label'  => 'Quản lý Kho'
                ]
            ]
        ];
    }

    public function index(Request $request, $model=null)
    {
        // dd($request->all());
        if (!$model) {
            $model = 'bia';
        }
        $nameWarehouse = $this->checkExistModel($model);
        $types         = DataHelper::getModelWarehouses('plate');

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Danh sách ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $params                     = array();
        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }
        $warehouses = $this->warehouseService->search($model,$params);
        // dd($warehouses);
        $request->flash();
        return view('admins.warehouse_plates.index',compact('types', 'breadcrumb', 'titleForLayout', 'warehouses', 'model'));
    }

    public function create($model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.warehouse_plates.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'model'));
    }

    public function store(WarehousePlateRequest $request, $model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $input = $request->except('_token');
        if ($this->warehouseService->storeOrUpdate($model, $input)) {
            return redirect()->route('admin.warehouse-plate.index', ['model' => $model])->with('success','Tạo Vật Liệu thành công!');
        }
        return redirect()->route('admin.warehouse-plate.index', ['model' => $model])->with('error','Tạo Vật Liệu thất bại!');
    }

    public function edit(Request $request, $model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];

        $warehousePlate = $this->warehouseService->edit($id,$model);
        if ($warehousePlate) {
            $permissions = config('permission.permissions');
            return view('admins.warehouse_plates.edit',compact('breadcrumb', 'titleForLayout', 'warehousePlate', 'permissions', 'model'));
        }
        return redirect()->route('admin.warehouse-plate.index', ['model' => $model])->with('error', 'Vật Liệu không tồn tại!');
    }

    public function update(WarehousePlateRequest $request, $model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $input          = $request->except('_token', '_method');
        $input['l_id'] = $id;
        if ($this->warehouseService->storeOrUpdate($model, $input)) {
            return redirect()->route('admin.warehouse-plate.edit', ['model' => $model, 'id' => $id])->with('success','Cập nhật Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function destroy($model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);
        if ($this->warehouseService->delete($id,$model)) {
            return redirect()->route('admin.warehouse-plate.index', ['model' => $model])->with('success','Xóa Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function import(Request $request, $model) {
        $nameWarehouse = $this->checkExistModel($model);

        try{
            set_time_limit(0);
            $file = $request->file('import-plate');
            $ext = DataHelper::getExtensionImport($file->extension());
            if ($ext) {
                \DB::beginTransaction();
                Excel::import(new WarehouseImport($model), $file);
                \DB::commit();
                return redirect()->back()->with('success','Đã import dữ liệu thành công.');
            }
        // } catch (\Maatwebsite\Excel\Validators\ValidationException $ex) {
        //     Log::error('--- Lỗi import: '.$nameWarehouse.' ---');
        //     $errorMessages = [];
        //     $failures      = $ex->failures();
        //     foreach ($failures as $failure) {
        //         foreach($failure->errors() as $error) {
        //             $errorMessages[] = 'Có một lỗi xảy ra trên dòng số '.$failure->row().'. '.$error;
        //         }
        //     }
        //     Session::flash('errors', $errorMessages);
        //     Log::error($ex->errors());
        //     Log::error('--- End: Lỗi import: '.$nameWarehouse.'  ---');
        //     \DB::rollback();
        //     return redirect()->back();
        } catch(\Exception $ex) {
            Log::error('--- Lỗi import: '.$nameWarehouse.'  ---');
            Log::error($ex);
            Log::error('--- End: Lỗi import: '.$nameWarehouse.'  ---');
            \DB::rollback();
        }
        return redirect()->back()->with('error','Quá trình import dữ liệu thất bại.');
    }

    private function checkExistModel($data) {
        $typeModels = DataHelper::getModelWarehouses('plate');
        if (!array_key_exists($data, $typeModels)) {
            dd('Thông tin không hợp lệ.');
        }
        return $typeModels[$data];
    }

    public function showFormCreate(Request $request) {
        $model = WarehouseHelper::warehouseModelPath($request->model_type);
        $viewPath = WarehouseHelper::warehouseFormCreate($request->model_type);
        return view($viewPath,compact('model'));
    }
}
