<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseSpwRequest;
use App\Imports\Spws\WarehouseSpwsImport;
use App\Models\Repositories\WarehouseSpwRepository;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use \Log;

class WarehouseSpwController extends Controller
{
    protected $warehouseSpwRepository;
    protected $warehouseService;
    public $menu;

    function __construct(
        WarehouseSpwRepository $warehouseSpwRepository,
        WarehouseService $warehouseService
    ) {
        $this->warehouseSpwRepository = $warehouseSpwRepository;
        $this->warehouseService = $warehouseService;
        $this->menu                     = [
            'root' => 'Quản lý Kho SPW',
            'data' => [
                'parent' => [
                    'href'   => route('admin.warehouse-spw.index'),
                    'label'  => 'Quản lý Kho SPW'
                ]
            ]
        ];
    }

    public function index(Request $request, $model=null)
    {
        if (!$model) {
            $model = 'filler';
        }
        $nameWarehouse = $this->checkExistModel($model);
        $types         = DataHelper::getModelWarehouses('spw');

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Danh sách ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $params                     = array();
        $limit                      = 10;
        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        $warehouseSpws = $this->warehouseSpwRepository->getWarehouseSpws($model, $params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_spws.index',compact('types', 'breadcrumb', 'titleForLayout', 'warehouseSpws', 'model'));
    }

    public function create($model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.warehouse_spws.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'model'));
    }

    public function store(WarehouseSpwRequest $request, $model)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $input = $request->except('_token');
        if ($this->warehouseService->storeOrUpdate($model, $input)) {
            return redirect()->route('admin.warehouse-spw.index', ['model' => $model])->with('success','Tạo Vật Liệu thành công!');
        }
        return redirect()->route('admin.warehouse-spw.index', ['model' => $model])->with('error','Tạo Vật Liệu thất bại!');
    }

    public function edit(Request $request, $model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật ' . $nameWarehouse];
        $titleForLayout             = $breadcrumb['data']['list']['label'];

        $warehouseSpw = $this->warehouseSpwRepository->find($model, $id);
        if ($warehouseSpw) {
            $permissions = config('permission.permissions');
            return view('admins.warehouse_spws.edit',compact('breadcrumb', 'titleForLayout', 'warehouseSpw', 'permissions', 'model'));
        }
        return redirect()->route('admin.warehouse-spw.index', ['model' => $model])->with('error', 'Vật Liệu không tồn tại!');
    }

    public function update(WarehouseSpwRequest $request, $model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $input          = $request->except('_token', '_method');
        $warehouseSpw = $this->warehouseSpwRepository->find($model, $id);
        if ($warehouseSpw) {
            $this->warehouseSpwRepository->update($model, $input, $warehouseSpw);
            return redirect()->route('admin.warehouse-spw.edit', ['model' => $model, 'id' => $id])->with('success','Cập nhật Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function destroy($model, $id)
    {
        $nameWarehouse = $this->checkExistModel($model);

        $warehouseSpw = $this->warehouseSpwRepository->find($model, $id);
        if ($warehouseSpw) {
            $warehouseSpw->delete();
            return redirect()->route('admin.warehouse-spw.index', ['model' => $model])->with('success','Xóa Vật Liệu thành công!');
        }
        return redirect()->back()->with('error', 'Vật Liệu không tồn tại!');
    }

    public function import(Request $request, $model) {
        $nameWarehouse = $this->checkExistModel($model);

        try{
            set_time_limit(0);
            $file = $request->file('import-spw');
            $ext = DataHelper::getExtensionImport($file->extension());
            if ($ext) {
                \DB::beginTransaction();
                Excel::import(new WarehouseSpwsImport($model), $file);
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
        $typeModels = DataHelper::getModelWarehouses('spw');
        if (!array_key_exists($data, $typeModels)) {
            dd('Thông tin không hợp lệ.');
        }
        return $typeModels[$data];
    }
}
