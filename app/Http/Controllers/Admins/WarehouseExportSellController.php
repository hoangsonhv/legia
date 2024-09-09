<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CoreCustomer;
use App\Models\Receipt;
use App\Models\WarehouseExportSell;
use Illuminate\Http\Request;
use App\Enums\ProcessStatus;
use App\Helpers\WarehouseHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\WarehouseExportSellRequest;
use App\Models\Co;
use App\Models\Repositories\WarehouseExportSellRepository;
use App\Models\Repositories\CoRepository;
use Illuminate\Support\Facades\Storage;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\CoStepHistory;
use App\Models\MerchandiseGroup;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Models\Warehouse\Group11;

class WarehouseExportSellController extends Controller
{
    private $disk = 'local';
    /**
     * @var
     */
    protected $whExportSellRepo;

    /**
     * @var
     */
    protected $coRepo;

    /**
     * @var
     */
    protected $coStepHisRepo;
    /**
     * @var array
     */
    public $menu;

    function __construct(WarehouseExportSellRepository $whExportSellRepo, CoRepository $coRepo,
                         CoStepHistoryRepository $coStepHisRepo)
    {
        $this->whExportSellRepo = $whExportSellRepo;
        $this->coRepo = $coRepo;
        $this->coStepHisRepo = $coStepHisRepo;
        $this->menu = [
            'root' => 'Quản lý Phiếu xuất kho bán hàng',
            'data' => [
                'parent' => [
                    'href' => route('admin.warehouse-export-sell.index'),
                    'label' => 'Phiếu xuất kho bán hàng'
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
        $coreCustomers = AdminHelper::getCoreCustomer();

        // search
        if ($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        if ($request->input('core_customer_id')) {
            $params['core_customer_id'] = $request->core_customer_id;
        }

        if (in_array($request->input('status_customer_received'), ['0', '1'])) {
            $params['status_customer_received'] = $request->status_customer_received;
        }

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.warehouse-export-sell.index-all')) {
            $params['created_by'] = $user->id;
        }

        $datas = $this->whExportSellRepo->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.warehouse_export_sell.index', compact('breadcrumb', 'titleForLayout', 'datas', 'coreCustomers'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $coreCustomers = AdminHelper::getCoreCustomer();
        $coreCustomerOrigin = CoreCustomer::all()->toArray();
        $model = null;
        $warehouses = [];

        $params = $request->all();
        $coId = isset($params['co_id']) ? $params['co_id'] : null;
        if ($coId) {
            $queryCo    = $this->coRepo->getCoes([
                'id'     => $coId,
                'status' => ProcessStatus::Approved
            ])->limit(1);
            $coModel = $queryCo->first();
            $warehouses  = $coModel->warehouses;
            $co = $queryCo->pluck('code', 'id')->toArray();
            if (!$co) {
                return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
            }

            $products = [];
            // foreach ($warehouses as $warehouse) {
            //     $merchandise_id = $warehouse['merchandise_id'];
            //     $need_quantity = $warehouse['so_luong'];
            //     $base_warehouse = BaseWarehouseCommon::where('l_id', $merchandise_id)->first();
            //     $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)->where('code', $warehouse['code'])->get()
            //         ->groupBy('lot_no')->map(function ($group) {
            //             $totalQuantity = $group->sum(array_keys($group[0]->ton_kho)[0]);
            //             if ($totalQuantity > 0) {
            //                 // Thêm thuộc tính `totalQuantity` cho mỗi instance
            //                 $group->each(function ($product) use ($totalQuantity) {
            //                     $product->totalQuantity = $totalQuantity;
            //                 });
            //                 return $group;
            //             }
            //             return null;
            //         })->filter()->flatten()->values()
            //         ->map(function($item) use($warehouse, $merchandise_id, &$products, &$need_quantity){
            //             if($item->quantity > 0) {
            //                 if($item->quantity >= $need_quantity) {
            //                     $quantity = $need_quantity;
            //                     $need_quantity = 0;
            //                 } else {
            //                     $quantity = $item->quantity;
            //                     $need_quantity = $need_quantity - $item->quantity;
            //                 }
            //                 array_push($products, [
            //                     'code' => $warehouse['code'],
            //                     'name' => $warehouse['loai_vat_lieu'],
            //                     'unit' => $warehouse['dv_tinh'],
            //                     'lot_no' => $item->lot_no,
            //                     'model_type' => $item->model_type,
            //                     'ton_kho' => $item->ton_kho,
            //                     'quantity' => $quantity,
            //                     'unit_price' => $warehouse['don_gia'],
            //                     'into_money' => $warehouse['don_gia']*$quantity,
            //                     'merchandise_id' => $merchandise_id,
            //                 ]);

            //             }
            //         });
            // }
        }
        return view('admins.warehouse_export_sell.create', compact('breadcrumb', 'titleForLayout',
            'permissions', 'warehouses', 'coreCustomers', 'coreCustomerOrigin', 'coModel', 'co', 'products', 'model'));
    }

    public function store(WarehouseExportSellRequest $request)
    {
        $input = $request->input();
       	// dd($input);
        try {
            // Upload file
            $files     = $request->file('document');
            $documents = [];
            if ($files) {
                $path = 'uploads/warehouse_export_sell/document';
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
            // Save warehouse export sell
            $input['code'] = $this->whExportSellRepo->generateCode();
            $input['currency'] = WarehouseExportSell::CURRENCY_VND;
            $input['created_by'] = Session::get('login')->id;
            $input['document'] = json_encode($documents);
            $model = $this->whExportSellRepo->insert($input);
            $inputProducts = $request->input('product');

            if($model && $model->co_id) {
                $receipt = Receipt::where('co_id', $model->co_id)
                    ->where('step_id', 1)
                    ->where('status', ProcessStatus::Pending)
                    ->first();
                // Nếu phiếu thu lần 3 == 0 thì chuyển xuất kho bán hàng
                $hasPercent = $this->coRepo->checkPercentPayment($model->co_id, 2);
                if($receipt) {
                    $this->coStepHisRepo->insertNextStep( 'receipt', $model->co_id, $receipt->id, CoStepHistory::ACTION_APPROVE, 1);
                } else if(!$hasPercent) {
                    $this->coStepHisRepo->insertNextStep('delivery', $model->co_id, $model->co_id, CoStepHistory::ACTION_CREATE);
                }else {
                    $this->coStepHisRepo->insertNextStep( 'receipt', $model->co_id, $model->co_id, CoStepHistory::ACTION_CREATE, 2);
                }
                $co = Co::find($model->co_id);
                // dd($co->warehouseExports->flatMap);
                $productsExportPre = $co->warehouseExports->flatMap->products;
                foreach ($inputProducts['code'] as $key => $code) {
                    if (empty($code)) {
                        continue;
                    }
                    $isExported = $productsExportPre->first(function($pro) use($inputProducts, $key) {
                        return $pro->code == $inputProducts['code'][$key] && $pro->lot_no === $inputProducts['code'][$key];
                    });
                    if($isExported) {
                        $base_warehouse = BaseWarehouseCommon::find($inputProducts['merchandise_id'][$key]);
                        $group_warehouse = WarehouseHelper::getModel($base_warehouse->model_type)
                            ->find($inputProducts['merchandise_id'][$key]);
                        $group_warehouse->setQuantity($isExported->quantity_reality);
                    }
                    $products[] = [
                        'code' => $inputProducts['code'][$key],
                        'name' => $inputProducts['name'][$key],
                        'do_day' => $inputProducts['do_day'][$key],
                        'tieu_chuan' => $inputProducts['tieu_chuan'][$key],
                        'kich_co' => $inputProducts['kich_co'][$key],
                        'kich_thuoc' => $inputProducts['kich_thuoc'][$key],
                        'chuan_bich' => $inputProducts['chuan_bich'][$key],
                        'chuan_gasket' => $inputProducts['chuan_gasket'][$key],
                        'unit' => $inputProducts['unit'][$key],
                        'quantity' => $inputProducts['quantity'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                        'lot_no' => $inputProducts['lot_no'][$key],
                        'vat' => $inputProducts['vat'][$key],
                        'merchandise_id' => $inputProducts['merchandise_id'][$key],
                    ];
                }
            }
            else {
                // Save many product
                foreach ($inputProducts['code'] as $key => $code) {
                    if (empty($code)) {
                        continue;
                    }
                    $products[] = [
                        'code' => $inputProducts['code'][$key],
                        'name' => $inputProducts['name'][$key],
                        'do_day' => $inputProducts['do_day'][$key],
                        'tieu_chuan' => $inputProducts['tieu_chuan'][$key],
                        'kich_co' => $inputProducts['kich_co'][$key],
                        'kich_thuoc' => $inputProducts['kich_thuoc'][$key],
                        'chuan_bich' => $inputProducts['chuan_bich'][$key],
                        'chuan_gasket' => $inputProducts['chuan_gasket'][$key],
                        'unit' => $inputProducts['unit'][$key],
                        'quantity' => $inputProducts['quantity'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                        'vat' => $inputProducts['vat'][$key],
                        'lot_no' => $inputProducts['lot_no'][$key],
                        'merchandise_id' => $inputProducts['merchandise_id'][$key],
                    ];
                }
            }


            if (!empty($products)) {
                $model->products()->createMany($products);
                // Decrease material in warehouse
                foreach ($products as $product) {
                    if ($product['merchandise_id'] > 0) {
                        $base_warehouse = BaseWarehouseCommon::find($product['merchandise_id']);
                        $group_warehouse = WarehouseHelper::getModel($base_warehouse->model_type)
                            ->find($product['merchandise_id']);
                        $group_warehouse->setQuantity($product['quantity'] * (-1));
                        $group_warehouse->save();
                    }
                }

                \DB::commit();
                return redirect()->route('admin.warehouse-export-sell.index')->with('success', 'Tạo phiếu xuất kho bán hàng thành công!');
            }
        } catch (\Exception $ex) {
            \DB::rollback();
            //dd($ex);
	    report($ex);
        }
        return redirect()->route('admin.warehouse-export-sell.index')->with('error', 'Tạo phiếu xuất kho bán hàng thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];

        $model = $this->whExportSellRepo->find($id);
        if ($model) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.warehouse-export-sell.index-all') && $model->created_by != $user->id) {
                return redirect()->route('admin.warehouse-export-sell.index')->with('error', 'Bạn không có quyền truy cập!');
            }

            $permissions = config('permission.permissions');
            $coreCustomers = AdminHelper::getCoreCustomer();
            $coreCustomerOrigin = CoreCustomer::all()->toArray();
            $model->products->map(function($item){
                $base_warehouse = BaseWarehouseCommon::where('l_id', $item->merchandise_id)->first();
                $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)->where('code',$item->code)->where('lot_no',$item->lot_no)->first();
                $item->model_type = $base_warehouse->model_type;
                // dump($item->code, $item->lot_no);
                $item->ton_kho = $merchandise->ton_kho ?? 0;
                return $item;
            });
            $products = $model->products->toArray();

            $coId = $model->co_id ?? null;
            if ($coId) {
                $queryCo = $this->coRepo->getCoes([
                    'id' => $coId,
                    'status' => ProcessStatus::Approved
                ])->limit(1);
                $co = $queryCo->pluck('code', 'id')->toArray();
                if (!$co) {
                    return redirect()->back()->with('error', 'Vui lòng kiểm tra lại CO!');
                }
            }

            return view('admins.warehouse_export_sell.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'coreCustomers', 'coreCustomerOrigin', 'products', 'co'));
        }
        return redirect()->route('admin.warehouse-export-sell.index')->with('error', 'Phiếu xuất kho bán hàng không tồn tại!');
    }

    public function update(WarehouseExportSellRequest $request, $id)
    {
        $model = $this->whExportSellRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            try {
                $coModel = $this->coRepo->find($request->input('co_id'));
                if (!$coModel || $coModel->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }

                $files = $request->file('document');
                $documents = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/warehouse_export_sell/document';
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
                $model = $this->whExportSellRepo->update($inputs, $id);

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
                        'quantity' => $inputProducts['quantity'][$key],
                        'lot_no' => $inputProducts['lot_no'][$key],
                        'unit_price' => $inputProducts['unit_price'][$key],
                        'into_money' => $inputProducts['into_money'][$key],
                        'merchandise_id' => $inputProducts['merchandise_id'][$key],
                    ];
                }

                if (!empty($model)) {
                    $model->products()->createMany($products);

                    // // Decrease material in base warehouse
                    // foreach ($products as $product) {
                    //     if ($product['merchandise_id'] > 0) {
                    //         $warehouseModel = Group11::find($product['merchandise_id']);
                    //         $warehouseModel->setQuantity($product['quantity'] * (-1));
                    //         $warehouseModel->save();
                    //     }
                    // }

                    \DB::commit();
                    return redirect()->route('admin.warehouse-export-sell.edit', ['id' => $id])->with('success', 'Cập nhật Phiếu xuất kho bán hàng thành công!');
                }
            } catch (\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật phiếu xuất kho bán hàng không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Phiếu xuất kho bán hàng không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->whExportSellRepo->find($id);
        if ($model) {
            $model->products()->delete();
            $model->delete();
            return redirect()->route('admin.warehouse-export-sell.index')->with('success', 'Xóa phiếu xuất kho bán hàng thành công!');
        }
        return redirect()->back()->with('error', 'Phiếu xuất kho bán hàng không tồn tại!');
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->whExportSellRepo->find($id);
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
