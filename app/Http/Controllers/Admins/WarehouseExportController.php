<?php

namespace App\Http\Controllers\Admins;

use App\Enums\QCCheckStatus;
use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseExportRequest;
use App\Http\Requests\WarehouseReceiptRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\CoStepHistory;
use App\Models\Manufacture;
use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\WarehouseReceiptRepository;
use App\Models\Repositories\CoRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Repositories\WarehouseExportRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\ManufactureRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\RequestStepHistoryRepository;
use App\Models\Repositories\WarehouseHistoryRepository;
use App\Models\Request as ModelsRequest;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Services\CoService;

class WarehouseExportController extends Controller
{
    private $disk = 'local';
    /**
     * @var
     */
    protected $whExportRepo;
    protected $coStepHisRepo;
    protected $requestRepo;
    protected $warehouseHistoryRepo;
    protected $manufactureRepo;
    protected $coService;

    /**
     * @var array
     */
    public $menu;

    function __construct(WarehouseExportRepository $whExportRepo,
                        CoStepHistoryRepository $coStepHisRepo,
                        RequestRepository $requestRepo,
                        CoService $coService,
                        ManufactureRepository $manufactureRepo,
                        WarehouseHistoryRepository $warehouseHistoryRepo)
    {
        $this->whExportRepo = $whExportRepo;
        $this->manufactureRepo = $manufactureRepo;
        $this->coStepHisRepo = $coStepHisRepo;
        $this->coService             = $coService;
        $this->requestRepo             = $requestRepo;
        $this->warehouseHistoryRepo = $warehouseHistoryRepo;
        $this->menu = [
            'root' => 'Quản lý Phiếu xuất kho',
            'data' => [
                'parent' => [
                    'href' => route('admin.warehouse-export.index'),
                    'label' => 'Phiếu xuất kho'
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

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.warehouse-export.index-all')) {
            $params['created_by'] = $user->id;
        }

        $datas = $this->whExportRepo->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_export.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $model = null;
        $products = [];
        $co = null;
        $warehouses = null;
        $listWarehouse = null;
        $coId = $request->has('co_id') ? $request->co_id : null;
        $requestId = $request->has('request_id') ? $request->request_id : null;
        if ($coId) {
            $co = Co::find($coId);
            $warehouses    = $co->warehouses;
            $listWarehouse = $this->coService->getProductMaterialsInWarehouses($warehouses->pluck('code', 'id')->toArray());
        }
        $listCo = Co::where('status', 2)->where('confirm_done', 0)->orderBy('id', 'DESC')->get()->pluck('code', 'id');
        $listCo = $listCo->prepend('Chọn mã CO', 0);
        return view('admins.warehouse_export.create', compact('breadcrumb', 'titleForLayout',
            'permissions', 'products', 'model', 'coId', 'listCo', 'co', 'listWarehouse', 'warehouses','requestId'));
    }

    public function store(WarehouseExportRequest $request)
    {
        $input = $request->input();
        try {
            // Upload file
            $files     = $request->file('document');
            $documents = [];
            if ($files) {
                $path = 'uploads/warehouse_export/document';
                foreach($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($documents) {
                            foreach($documents as $document) {
                                Storage::disk($this->disk)->delete($document);
                            }
                        }
                        return redirect()->back()->withInput()->with('error','File upload bị lỗi! Vui lòng kiểm tra lại file.');
                    }
                    $documents[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }

            \DB::beginTransaction();
            // Save warehouse receipt
            $input['code'] = $this->whExportRepo->generateCode();
            $input['created_by'] = Session::get('login')->id;
            $input['document'] = json_encode($documents);
            $model = $this->whExportRepo->insert($input);
            if(($model && $model->co_id) || (isset($input['co_id']) && $input['co_id'])) {
                $co = Co::find($model->co_id ?? $input['co_id']);
                if($co->is_remake) {
                    $manufactures = $this->manufactureRepo->findExtend([
                        'co_id' => $model->co_id
                    ])->update([
                        'qc_check' => QCCheckStatus::WAITING
                    ]);
                    $this->manufactureRepo->checkNeedQuantity($model->co_id);
                }
                $this->coStepHisRepo->insertNextStep('manufacture', $model->co_id, $model->co_id, CoStepHistory::ACTION_APPROVE);
            }
            // Save many product
            $inputProducts = $request->input('product');
            $sum_quantity_reality = [];
            if($inputProducts) {
                foreach ($inputProducts['code'] as $key => $code) {
                    if (empty($code)) {
                        continue;
                    }
                    $products[] = [
                        'code' => $inputProducts['code'][$key],
                        'name' => $inputProducts['name'][$key],
                        'unit' => $inputProducts['unit'][$key],
                        'quantity_doc' => $inputProducts['quantity_doc'][$key],
                        'quantity_reality' => $inputProducts['quantity_reality'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                        'merchandise_id' => $inputProducts['merchandise_id'][$key],
                        'lot_no' => $inputProducts['lot_no'][$key],
                        'table_name' => $inputProducts['table_name'][$key],
                    ];
                    $sum_quantity_reality[] = [
                        'code' => $inputProducts['code'][$key],
                        'quantity_reality' => $inputProducts['quantity_reality'][$key],
                    ];
                }
            }
            if (!empty($products)) {
                $model->products()->createMany($products);
                
                // Decrease material in base warehouse
                foreach ($products as $product) {
                    if ($product['merchandise_id'] > 0) {
                        $base_warehouse = BaseWarehouseCommon::find($product['merchandise_id']);
                        $group_warehouse = WarehouseHelper::getModel($base_warehouse->model_type)->find($product['merchandise_id']);
                        $group_warehouse->setQuantity($product['quantity_reality'] * (-1));
                        $group_warehouse->save();
                    }
                }

                //$this->warehouseHistoryRepo->updateWarehouseHistory($products, WarehouseHistory::TYPE_WAREHOUSE_EXPORT);
            }
            if ($model && $model->request_id) {
                $modelRequest = ModelsRequest::find($model->request_id);
                $groupedAndSummedArray = array_reduce($sum_quantity_reality, function($result, $item) {
                    $code = $item['code'];
                    $quantity = $item['quantity_reality'];
                
                    if (!isset($result[$code])) {
                        $result[$code] = 0;
                    }
                
                    $result[$code] += $quantity;
                
                    return $result;
                }, array());
                $material = $modelRequest->material;
                // $isDone = false;
                // foreach($material as $item ) {
                //     if($item->dinh_luong <= $groupedAndSummedArray[$item->code]) {
                //         $isDone = true;
                //     } else {
                //         $isDone = false;
                //         break;
                //     }
                // }
                // if($isDone) {
                //     $this->requestRepo->doneRequest($model->request_id);
                // }
            }
            \DB::commit();
            return redirect()->route('admin.warehouse-export.index')->with('success', 'Tạo phiếu xuất kho thành công!');
        } catch (\Exception $ex) {
            \DB::rollback();
            dd($ex);
        }
        return redirect()->route('admin.warehouse-export.index')->with('error', 'Tạo phiếu xuất kho thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];

        $model = $this->whExportRepo->find($id);
        if ($model) {
            $user = Session::get('login');
            if(( !PermissionHelper::hasPermission('admin.warehouse-export.index-all') && $model->created_by != $user->id)) {
                return redirect()->route('admin.warehouse-export.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions = config('permission.permissions');
            $co = null;
            $warehouses = null;
            $listWarehouse = null;
            $coId = $model->co_id;
            if ($coId) {
                $co = Co::find($coId);
                $warehouses    = $co->warehouses;
                $listWarehouse = $this->coService->getProductMaterialsInWarehouses($co->warehouses->pluck('code', 'id')->toArray());
            }

            $products = $model->products->toArray();
            return view('admins.warehouse_export.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'products', 'co', 'listWarehouse', 'warehouses'));
        }
        return redirect()->route('admin.warehouse_export.index')->with('error', 'Phiếu xuất kho không tồn tại!');
    }

    public function update(WarehouseExportRequest $request, $id)
    {
        $model = $this->whExportRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            try {
                $files = $request->file('document');
                $documents = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/warehouse_export/document';
                    foreach ($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($documents) {
                                foreach ($documents as $document) {
                                    Storage::disk($this->disk)->delete($document);
                                }
                            }
                            return redirect()->back()->withInput()->with('error', 'File upload bị lỗi! Vui lòng kiểm tra lại file.');
                        }
                        $documents[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                $documents = array_merge(json_decode($model->document, true), $documents);

                \DB::beginTransaction();
                // Save request
                $inputs['document'] = json_encode($documents);
                $model = $this->whExportRepo->update($inputs, $id);

                // Save relationship
                $model->products()->delete();
                $inputProducts = $request->input('product');
                foreach ($inputProducts['code'] as $key => $code) {
                    if (empty($code)) {
                        continue;
                    }
                    $products[] = [
                        'code' => $inputProducts['code'][$key],
                        'name' => $inputProducts['name'][$key],
                        'unit' => $inputProducts['unit'][$key],
                        'quantity_doc' => $inputProducts['quantity_doc'][$key],
                        'quantity_reality' => $inputProducts['quantity_reality'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                    ];
                }

                if (!empty($model)) {
                    $model->products()->createMany($products);
                    \DB::commit();
                    return redirect()->route('admin.warehouse-export.edit', ['id' => $id])->with('success', 'Cập nhật Phiếu xuất kho thành công!');
                }
            } catch (\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật phiếu xuất kho không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Phiếu xuất kho không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->whExportRepo->find($id);
        if ($model) {
            $model->products()->delete();
            $model->delete();
            return redirect()->route('admin.warehouse-export.index')->with('success', 'Xóa Phiếu xuất kho thành công!');
        }
        return redirect()->back()->with('error', 'Phiếu nhập kho không tồn tại!');
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->whExportRepo->find($id);
            if ($data) {
                $files = json_decode($data->document, true);
                if ($files) {
                    $path = $request->input('path');
                    foreach($files as $key => $file) {
                        if ($file['path'] === $path) {
                            Storage::disk($this->disk)->delete($file['path']);
                            unset($files[$key]);
                            $data->document = json_encode(array_values($files));
                            $data->save();
                            return ['success' => true];
                        }
                    }
                }
            }
        } catch(\Exception $ex) {
            report($ex);
        }
        return ['success' => false];
    }
}