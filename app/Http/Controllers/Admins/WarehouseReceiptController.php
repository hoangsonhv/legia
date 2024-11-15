<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseReceiptRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\CoStepHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\WarehouseReceiptRepository;
use App\Models\Repositories\CoRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Request as RequestModel;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\RequestStepHistoryRepository;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use App\Models\Warehouse\BaseWarehouseCommon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WarehouseReceiptController extends Controller
{
    private $disk = 'local';
    /**
     * @var
     */
    protected $whReceiptRepo;
    protected $coRepo;
    protected $coStepHisRepo;
    protected $requestRepo;
    protected $requestStepHisRepo;

    /**
     * @var array
     */
    public $menu;

    function __construct(WarehouseReceiptRepository $whReceiptRepo,
                         CoRepository $coRepo,
                         CoStepHistoryRepository $coStepHisRepo,
                         RequestRepository $requestRepo,
                         RequestStepHistoryRepository $requestStepHisRepo)
    {
        $this->whReceiptRepo    = $whReceiptRepo;
        $this->coRepo           = $coRepo;
        $this->coStepHisRepo    = $coStepHisRepo;
        $this->requestStepHisRepo    = $requestStepHisRepo;
        $this->requestRepo      = $requestRepo;
        $this->menu = [
            'root' => 'Quản lý Phiếu nhập kho',
            'data' => [
                'parent' => [
                    'href' => route('admin.warehouse-receipt.index'),
                    'label' => 'Phiếu nhập kho'
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
        if(!PermissionHelper::hasPermission('admin.warehouse-receipt.index-all')) {
            $params['created_by'] = $user->id;
        }
        $datas = $this->whReceiptRepo->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_receipt.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $model = null;
        $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM]);

        $inputs = $request->input();
        $products = [];
        $coModel = null;
        $request_id = $inputs['request_id'] ?? null;
        if(!empty($inputs['request_id'])) {
            $request = RequestModel::find($inputs['request_id']);
            if($request && ($request->co_id || in_array($request->category, array_keys($categories)))) {
                $queryCo    = $this->coRepo->getCoes([
                    'id'     => $request->co_id,
                    'status' => ProcessStatus::Approved
                ])->limit(1);
                $coModel = $queryCo->first();
                $co = $queryCo->pluck('code', 'id')->toArray();
                // if (!$co || !in_array($request->category, array_keys($categories))) {
                //     return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
                // }
                if ($request->material) {
                    $currentLotNo = DB::table('base_warehouses')
                        ->select(DB::raw('MAX(REPLACE(lot_no,"NK","")) AS max_lot_no'))
                        ->where('lot_no', 'like', 'NK%')
                        ->first();
                    $nextLotNo = 'NK'.(intval($currentLotNo->max_lot_no)+1);
                    foreach ($request->material as $material) {
                        $price_survey = $material->price_survey->where('status', \App\Models\PriceSurvey::TYPE_BUY)->first();
                        $products[] = [
                            'merchandise_id' => $material->merchandise_id,
                            'code' => $material->code,
                            'do_day' => $material->do_day,
                            'hinh_dang' => $material->hinh_dang,
                            'dia_w_w1' => $material->dia_w_w1,
                            'l_l1' => $material->l_l1,
                            'w2' => $material->w2,
                            'l2' => $material->l2,
                            'name' => $material->mo_ta,
                            'kich_thuoc' => $material->kich_thuoc,
                            'quy_cach' => $material->quy_cach,
                            'unit' => $material->dv_tinh,
                            'lot_no' => $nextLotNo,
                            'quantity_doc' => $material->dinh_luong,
                            'quantity_reality' => 0,
                            'unit_price' => ($price_survey->price / $material->dinh_luong),
                            'into_money' => $price_survey->price,
                            'model_type' => in_array($request->category, array_keys($categories)) ? WarehouseHelper::KHO_VAT_DUNG : null,
                        ];
                    }
                }
                else {
                    $warehouses    = $coModel->warehouses;
                    foreach ($warehouses as $warehouse) {
                        $products[] = [
                            'merchandise_id' => 0,
                            'code' => $warehouse->code,
                            'name' => $warehouse->loai_vat_lieu,
                            'unit' => $warehouse->dv_tinh,
                            'lot_no' => '',
                            'quantity_doc' => $warehouse->so_luong,
                            'quantity_reality' => 0,
                            'unit_price' => 0,
                            'into_money' => 0,
                        ];
                    }
                }
            }
        }
        $listCo = Co::where('status', 2)->where('confirm_done', 0)->orderBy('id', 'DESC')->get()->pluck('code', 'id');

        return view('admins.warehouse_receipt.create', compact('breadcrumb', 'titleForLayout',
            'permissions', 'products', 'model', 'coModel','request_id','listCo'));
    }

    public function store(WarehouseReceiptRequest $request)
    {
        $input = $request->input();
//        try {
            // Upload file
            $files     = $request->file('document');
            $documents = [];
            if ($files) {
                $path = 'uploads/warehouse_receipt/document';
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
            $input['code'] = $this->whReceiptRepo->generateCode();
            $input['created_by'] = Session::get('login')->id;
            $input['document'] = json_encode($documents);
            $model = $this->whReceiptRepo->insert($input);
            if($model && $model->co_id) {
                $co = Co::find($model->co_id);
                if($co->warehouseReceipts()->count() == 1) {
                    if($co && $co->request && $co->request[0]) {
                        // $this->coStepHisRepo->insertNextStep('payment', $model->co_id, $co->request[0]->id, CoStepHistory::ACTION_CREATE, 3);
                        $this->coStepHisRepo->insertNextStep('warehouse-export', $model->co_id, $model->co_id, CoStepHistory::ACTION_CREATE);
                    }
                    else {
                        $this->coStepHisRepo->insertNextStep('warehouse-export', $model->co_id, $model->co_id, CoStepHistory::ACTION_CREATE);
                    }
                }
            } else if($model && $model->request_id && !$model->co_id) {
                    $this->requestRepo->doneRequest($model->request_id);
                    // $this->requestStepHisRepo->insertNextStep('warehouse-export', $model->request_id, $model->request_id, CoStepHistory::ACTION_CREATE);
            }

            // Save many product
            $inputProducts = $request->input('product');
            foreach ($inputProducts['code'] as $key => $code) {
                if (empty($code)) {
                    continue;
                }
                $products[] = [
                    'merchandise_id' => $inputProducts['merchandise_id'][$key],
                    'code' => $inputProducts['code'][$key],
                    'do_day' => $inputProducts['do_day'][$key],
                    'hinh_dang' => $inputProducts['hinh_dang'][$key],
                    'dia_w_w1' => $inputProducts['dia_w_w1'][$key],
                    'l_l1' => $inputProducts['l_l1'][$key],
                    'w2' => $inputProducts['w2'][$key],
                    'l2' => $inputProducts['l2'][$key],
                    'name' => $inputProducts['name'][$key],
                    'unit' => $inputProducts['unit'][$key],
                    // 'kich_thuoc' => $inputProducts['kich_thuoc'][$key],
                    // 'quy_cach' => $inputProducts['quy_cach'][$key],
                    'lot_no' => $inputProducts['lot_no'][$key],
                    'quantity_doc' => $inputProducts['quantity_doc'][$key],
                    'quantity_reality' => $inputProducts['quantity_reality'][$key],
                    'unit_price' => $inputProducts['unit_price'][$key],
                    'into_money' => $inputProducts['into_money'][$key],
                ];
            }

            if (!empty($products)) {
                $model->products()->createMany($products);

                // Increase material in base warehouse
                foreach ($products as $product) {
                    if ($product['merchandise_id'] > 0) {
                        $base_warehouse = BaseWarehouseCommon::find($product['merchandise_id']);
                        
                        $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)
                            ->find($product['merchandise_id']);

                        $new_merchandise = $merchandise->replicate();
                        $new_merchandise->created_at = Carbon::now();
                        $new_merchandise->ghi_chu = "Đầu kỳ";
                        $new_merchandise->lot_no = $product['lot_no'];
                        $new_merchandise->date = Carbon::now();
                        $new_merchandise->setQuantity($product['quantity_reality'], accumulate: false);
                        $new_merchandise->save();
                    }
                }

                \DB::commit();
                return redirect()->route('admin.warehouse-receipt.index')->with('success', 'Tạo phiếu nhập kho thành công!');
            }
//        } catch (\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->route('admin.warehouse-receipt.index')->with('error', 'Tạo phiếu nhập kho thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];

        $model = $this->whReceiptRepo->find($id);
        if ($model) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.warehouse-receipt.index-all') && $model->created_by != $user->id) {
                return redirect()->route('admin.warehouse-receipt.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions = config('permission.permissions');
            $products = $model->products->toArray();
            return view('admins.warehouse_receipt.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'products'));
        }
        return redirect()->route('admin.warehouse_receipt.index')->with('error', 'Phiếu nhập kho không tồn tại!');
    }

    public function update(WarehouseReceiptRequest $request, $id)
    {
        $model = $this->whReceiptRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            try {
                $files = $request->file('document');
                $documents = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/warehouse_receipt/document';
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
                $model = $this->whReceiptRepo->update($inputs, $id);

                // Save relationship
                $model->products()->delete();
                $inputProducts = $request->input('product');
                foreach ($inputProducts['code'] as $key => $code) {
                    if (empty($code)) {
                        continue;
                    }
                    $products[] = [
                        'merchandise_id' => $inputProducts['merchandise_id'][$key],
                        'code' => $inputProducts['code'][$key],
                        'name' => $inputProducts['name'][$key],
                        'unit' => $inputProducts['unit'][$key],
                        'kich_thuoc' => $inputProducts['kich_thuoc'][$key],
                        'quy_cach' => $inputProducts['quy_cach'][$key],
                        'lot_no' => $inputProducts['lot_no'][$key],
                        'quantity_doc' => $inputProducts['quantity_doc'][$key],
                        'quantity_reality' => $inputProducts['quantity_reality'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                    ];
                }

                if (!empty($model)) {
                    $model->products()->createMany($products);

                   // Increase material in base warehouse
                    foreach ($products as $product) {
                        if ($product['merchandise_id'] > 0) {
                            $base_warehouse = BaseWarehouseCommon::find($product['merchandise_id']);
                            $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)
                                ->find($product['merchandise_id']);

                            $new_merchandise = $merchandise->replicate();
                            $new_merchandise->created_at = Carbon::now();
                            $new_merchandise->ghi_chu = "Đầu kỳ";
                            $new_merchandise->lot_no = $product['lot_no'];
                            $new_merchandise->date = Carbon::now();
                            $new_merchandise->setQuantity($product['quantity_reality'], accumulate: false);
                            $new_merchandise->save();

                            // Remove old merchandise with same lot_no
                            $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)
                                ->where('code', $product['code'])
                                ->where('lot_no', $product['lot_no'])
                                ->first();
                            if ($merchandise != null) {
                                $merchandise->delete();
                            }
                        }
                    }

                    \DB::commit();
                    return redirect()->route('admin.warehouse-receipt.edit', ['id' => $id])->with('success', 'Cập nhật Phiếu nhập kho thành công!');
                }
            } catch (\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật phiếu nhập kho không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Phiếu nhập kho không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->whReceiptRepo->find($id);
        if ($model) {
            $model->products()->delete();
            $model->delete();
            return redirect()->route('admin.warehouse-receipt.index')->with('success', 'Xóa Phiếu nhập kho thành công!');
        }
        return redirect()->back()->with('error', 'Phiếu nhập kho không tồn tại!');
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->whReceiptRepo->find($id);
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