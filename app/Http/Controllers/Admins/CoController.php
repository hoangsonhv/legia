<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\CoreCustomer;
use App\Models\CoStepHistory;
use App\Models\Delivery;
use App\Models\OfferPrice;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\CoTmpRepository;
use App\Models\Repositories\DeliveryRepository;
use App\Services\CoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\ManufactureRepository;
use App\Helpers\PermissionHelper;
use Hamcrest\Collection\IsEmptyTraversable;
use Illuminate\Support\Facades\Validator;

class CoController extends Controller
{
    private $disk = 'local';

    protected $coTmpRepository;
    protected $coRepository;
    protected $coService;
    protected $deliveryRepository;
    protected $coStepHisRepo;
    protected $manufactureRepo;

    public $menu;

    function __construct(
        CoTmpRepository $coTmpRepository,
        CoRepository $coRepository,
        CoService $coService,
        DeliveryRepository $deliveryRepository,
        CoStepHistoryRepository $coStepHisRepo,
        ManufactureRepository $manufactureRepo
    ) {
        $this->coTmpRepository    = $coTmpRepository;
        $this->coRepository       = $coRepository;
        $this->coService          = $coService;
        $this->deliveryRepository = $deliveryRepository;
        $this->coStepHisRepo      = $coStepHisRepo;
        $this->manufactureRepo    = $manufactureRepo;
        $this->menu               = [
            'root' => 'Quản lý CO',
            'data' => [
                'parent' => [
                    'href'   => route('admin.co.index'),
                    'label'  => 'Quản lý CO'
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
        $statuses                   = ProcessStatus::all(ProcessStatus::PendingSurveyPrice);
        $statuses[0]                = 'Chọn trạng thái';
        $cores                      = CoreCustomer::all();
        ksort($statuses);

        // Core customers
        $coreCustomers[0] = 'Chọn khách hàng';
        foreach ($cores as $index => $core) {
            $coreCustomers[$core->id] = '('. $core->code .') ' . $core->name;
        }

        // Count data pending
        $countPending = $this->coRepository->getCoes(['used' => 0])->count();
        
        // search
        if($request->has('used')) {
            $params['used'] = $request->used;
        } else if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
            if ($request->input('status')) {
                $params['status'] = $request->status;
            }
            if ($request->input('from_date')) {
                $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('from_date'))->format('Y-m-d 00:00:00');
                $params['created_at_01'] = ['created_at', '>=', $fromDate];
            }
            if ($request->input('to_date')) {
                $toDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('to_date'))->format('Y-m-d 23:59:59');
                $params['created_at_02'] = ['created_at', '<=', $toDate];
            }
            if ($request->input('core_customer_id')) {
                $params['core_customer_id'] = $request->core_customer_id;
            }
        }

        $arrParms = $request->all();
        $this->coRepository->setParams($params, $arrParms);

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.co.index-all')) {
            $params['admin_id'] = $user->id;
        }
        $coes = $this->coRepository->getCoes($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.coes.index',compact('breadcrumb', 'titleForLayout', 'statuses', 'countPending', 'coes', 'coreCustomers'));
    }

    public function create(Request $request, $coTmpId = null)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $co                         = null;
        $warehouses                 = null;
        $material                   = null;
        if ($coTmpId) {
            $coTmp = $this->coTmpRepository->find($coTmpId);
            if ($coTmp) {
                $co         = $coTmp;
                $warehouses = $co->warehouses;
                // Get list warehouse
                $request->request->add(['code' => $co->warehouses->pluck('code', 'id')->toArray()]);
                $material = $this->getDataWarehouse($request);
            }
        }
        $permissions = config('permission.permissions');
        return view('admins.coes.create',compact('breadcrumb', 'titleForLayout', 'co', 'warehouses', 'material', 'permissions', 'coTmpId'));
    }

    public function store(CoRequest $request)
    {
            if (
                $request->thanh_toan['percent']['truoc_khi_lam_hang'] + 
                $request->thanh_toan['percent']['truoc_khi_giao_hang'] + 
                $request->thanh_toan['percent']['ngay_khi_giao_hang'] + 
                $request->thanh_toan['percent']['sau_khi_giao_hang_va_cttt'] != 100
            ) {
                return redirect()->route('admin.co.create')->withErrors(["thanh_toan"=>"Vui lòng nhập đủ 100%"]);
            }
            $files             = $request->file('po_document');
            $poDocuments = [];
            if ($files) {
                $path = 'uploads/coes/po_document';
                foreach($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($poDocuments) {
                            foreach($poDocuments as $document) {
                                Storage::disk($this->disk)->delete($document);
                            }
                        }
                        return redirect()->back()->withInput()->with('error','File upload bị lỗi! Vui lòng kiểm tra lại file.');
                    }
                    $poDocuments[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }

            $files             = $request->file('contract_document');
            $contractDocuments = [];
            if ($files) {
                $path = 'uploads/coes/contract_document';
                foreach($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($contractDocuments) {
                            foreach($contractDocuments as $document) {
                                Storage::disk($this->disk)->delete($document);
                            }
                        }
                        return redirect()->back()->withInput()->with('error','File upload bị lỗi! Vui lòng kiểm tra lại file.');
                    }
                    $contractDocuments[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }

            $files           = $request->file('invoice_document');
            $invoiceDocument = [];
            if ($files) {
                $path = 'uploads/coes/invoice_document';
                foreach($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($invoiceDocument) {
                            foreach($invoiceDocument as $document) {
                                Storage::disk($this->disk)->delete($document);
                            }
                        }
                        return redirect()->back()->withInput()->with('error','File upload bị lỗi! Vui lòng kiểm tra lại file.');
                    }
                    $invoiceDocument[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }

            \DB::beginTransaction();

            $customer = $request->input('customer');
            // Save core customer
            $dataCoreCustomer = [
                'code' => $customer['code'],
                'name' => $customer['ten'],
                'tax_code' => $customer['mst'],
                'address' => $customer['dia_chi'],
                'phone' => $customer['dien_thoai'],
                'email' => $customer['email'],
            ];
            $coreCustomer = CoreCustomer::where(['code' => $dataCoreCustomer['code']])->first();
            if(!$coreCustomer) {
                $coreCustomer = CoreCustomer::create($dataCoreCustomer);
            }

            $coTmpId = $request->input('co_tmp_id') ? $request->input('co_tmp_id') : null;
            $input = [
                'code'                   => $this->coRepository->getIdCurrent(),
                'admin_id'               => Session::get('login')->id,
                'description'            => $request->input('description'),
                'so_bao_gia'             => $request->input('so_bao_gia'),
                'ngay_bao_gia'           => $request->input('ngay_bao_gia'),
                'sales'                  => $request->input('sales'),
                'thoi_han_bao_gia'       => $request->input('thoi_han_bao_gia'),
                'tong_gia'               => $request->input('tong_gia'),
                'vat'                    => $request->input('vat'),
                'dong_goi_va_van_chuyen' => $request->input('dong_goi_va_van_chuyen'),
                'noi_giao_hang'          => $request->input('noi_giao_hang'),
                'xuat_xu'                => $request->input('xuat_xu'),
                'thoi_gian_giao_hang'    => $request->input('thoi_gian_giao_hang'),
                'thanh_toan'             => $request->input('thanh_toan'),
                'co_tmp_id'              => $coTmpId,
                'contract_document'      => json_encode($contractDocuments),
                'invoice_document'       => json_encode($invoiceDocument),
                'po_document'            => json_encode($poDocuments),
                'core_customer_id'       => $coreCustomer ? $coreCustomer->id : 0
            ];

            $co = Co::create($input);
            if($co) {
                // Update co_step_history
                $this->coStepHisRepo->insertNextStep('co', $co->id, $co->id ,CoStepHistory::ACTION_CREATE);
            }
            // Update co tmps
            if($coTmpId) {
                $coTmp = $this->coTmpRepository->find($coTmpId);
                if($coTmp) {
                    $coTmp->co_id = $co->id;
                    $coTmp->co_not_approved_id = 0;
                    $coTmp->save();
                }
            }
            // Save customer
            $co->customer()->create($customer);
            // Save relation
            $codes       = $request->input('code');
            $loaiVatLieu = $request->input('loai_vat_lieu');
            $doDay       = $request->input('do_day');
            $tieuChuan   = $request->input('tieu_chuan');
            $kichCo      = $request->input('kich_co');
            $kichThuoc   = $request->input('kich_thuoc');
            $chuanBich   = $request->input('chuan_bich');
            $chuanGasket = $request->input('chuan_gasket');
            $dvTinh      = $request->input('dv_tinh');
            $soLuong     = $request->input('so_luong');
            $soLuongSanXuat = $request->input('so_luong_san_xuat');
            $donGia      = $request->input('don_gia');
            $manufactureType = $request->input('manufacture_type');
            $warehouseGroupId = $request->input('warehouse_group_id');
            $materialType = $request->input('material_type');

            foreach ($codes as $key => $code) {
                $offerPrices[] = [
                    'code'          => $code,
                    'loai_vat_lieu' => $loaiVatLieu[$key],
                    'do_day'        => $doDay[$key],
                    'tieu_chuan'    => $tieuChuan[$key],
                    'kich_co'       => $kichCo[$key],
                    'kich_thuoc'    => $kichThuoc[$key],
                    'chuan_bich'    => $chuanBich[$key],
                    'chuan_gasket'  => $chuanGasket[$key],
                    'dv_tinh'       => $dvTinh[$key],
                    'so_luong'      => $soLuong[$key],
                    'don_gia'       => $donGia[$key],
                    'so_luong_san_xuat' => $soLuongSanXuat[$key],
//                    'material_type' => in_array($key, $request->input('material_type') ?? [])
//                        ? OfferPrice::MATERIAL_TYPE_METAL : OfferPrice::MATERIAL_TYPE_NON_METAL
                    'manufacture_type' => $manufactureType[$key],
                    'warehouse_group_id' => $warehouseGroupId[$key],
                    'material_type' => $materialType[$key],
                ];
            }
            // Insert many warehouse
            $co->warehouses()->createMany($offerPrices);
            \DB::commit();
            return redirect()->route('admin.co.index')->with('success','Tạo CO `'.$input['code'].'` thành công!');
//        } catch(\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->back()->withInput()->with('error','Tạo CO thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];

        // $category = $request->input('category');
        $co = $this->coRepository->find($id);
        if ($co) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.co.index-all') && $co->admin_id != $user->id) {
                return redirect()->route('admin.co.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            /*$listCoables = $this->coRepository->getCoes(
                ['id' => $id],
                ['has' => $category]
            )->first();
            if ($listCoables) {
                $coables = $co->request->merge($co->payment);
            } else {
                $coables = collect([]);
            }
            $cateAll     = [
                'request' => 'Phiếu Yêu Cầu',
                'payment' => 'Phiếu Chi',
                'receipt' => 'Phiếu Thu',
            ];*/
            $warehouses  = $co->warehouses;
            $permissions = config('permission.permissions');
            $coTmpData   = $co->co_tmp()->first();
            if ($coTmpData) {
                $coTmp = $coTmpData->warehouses;
            } else {
                $coTmp = null;
            }
            $delivery = $co->delivery()->first();
            // Get list warehouse
            $request->request->add(['code' => $co->warehouses->pluck('code', 'id')->toArray()]);
            $listWarehouse = $this->getDataWarehouse($request);

            $arrReceipts = $co->receipt()->get()->toArray();
            $receipts = [];
            foreach ($arrReceipts as $recod) {
                if(in_array($recod['status'], [ProcessStatus::Pending, ProcessStatus::Approved])) {
                    $receipts[$recod['step_id']] = $recod;
                }
            }
            return view('admins.coes.edit',compact('breadcrumb', 'titleForLayout', 'co', 'permissions',
                'coTmp', 'delivery', 'warehouses', 'listWarehouse', 'receipts'));
        }
        return redirect()->route('admin.co.index')->with('error', 'CO không tồn tại!');
    }

    public function update(CoRequest $request, $id)
    {
        try {
            // $warehouses = $this->getDataWarehouse($request);
            // if (!$warehouses->count()) {
            //     throw new \Exception('Data not found.');
            // }
            $co = $this->coRepository->find($id);
            if ($co) {
                if($request->has('update_thanh_toan') && $request->update_thanh_toan) {
                    $co->thanh_toan = $request->input('thanh_toan');
                    $co->save();
                    return redirect()->route('admin.co.edit', ['id' => $id])->with('success','Cập nhật CO thành công!');
                }
                // Upload file
                $files             = $request->file('po_document');
                $poDocuments = [];
                if ($files) {
                    $path = 'uploads/coes/po_document';
                    foreach($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($poDocuments) {
                                foreach($poDocuments as $document) {
                                    Storage::disk($this->disk)->delete($document);
                                }
                            }
                            return redirect()->back()->withInput()->with('error','File upload hợp đồng bị lỗi! Vui lòng kiểm tra lại file.');
                        }
                        $poDocuments[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                if ($co->po_document) {
                    $poDocuments = array_merge(json_decode($co->po_document, true), $poDocuments);
                }

                $files             = $request->file('contract_document');
                $contractDocuments = [];
                if ($files) {
                    $path = 'uploads/coes/contract_document';
                    foreach($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($contractDocuments) {
                                foreach($contractDocuments as $document) {
                                    Storage::disk($this->disk)->delete($document);
                                }
                            }
                            return redirect()->back()->withInput()->with('error','File upload hợp đồng bị lỗi! Vui lòng kiểm tra lại file.');
                        }
                        $contractDocuments[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                if ($co->contract_document) {
                    $contractDocuments = array_merge(json_decode($co->contract_document, true), $contractDocuments);
                }

                $files           = $request->file('invoice_document');
                $invoiceDocument = [];
                if ($files) {
                    $path = 'uploads/coes/invoice_document';
                    foreach($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($invoiceDocument) {
                                foreach($invoiceDocument as $document) {
                                    Storage::disk($this->disk)->delete($document);
                                }
                            }
                            return redirect()->back()->withInput()->with('error','File upload hoá đơn bị lỗi! Vui lòng kiểm tra lại file.');
                        }
                        $invoiceDocument[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                if ($co->invoice_document) {
                    $invoiceDocument = array_merge(json_decode($co->invoice_document, true), $invoiceDocument);
                }

                \DB::beginTransaction();
                $co->description            = $request->input('description');
                $co->so_bao_gia             = $request->input('so_bao_gia');
                $co->ngay_bao_gia           = $request->input('ngay_bao_gia');
                $co->sales                  = $request->input('sales');
                $co->thoi_han_bao_gia       = $request->input('thoi_han_bao_gia');
                $co->tong_gia               = $request->input('tong_gia');
                $co->vat                    = $request->input('vat');
                $co->dong_goi_va_van_chuyen = $request->input('dong_goi_va_van_chuyen');
                $co->noi_giao_hang          = $request->input('noi_giao_hang');
                $co->xuat_xu                = $request->input('xuat_xu');
                $co->thoi_gian_giao_hang    = $request->input('thoi_gian_giao_hang');
                $co->thanh_toan             = $request->input('thanh_toan');
                $co->po_document          = json_encode($poDocuments);
                $co->invoice_document           = json_encode($invoiceDocument);
                $co->contract_document           = json_encode($contractDocuments);
                if (ProcessStatus::Approved == $co->status) {
                    $co->confirm_done = (int) $request->input('confirm_done');
                    if ($request->input('enough_money')) {
                        $co->enough_money = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                    } else {
                        $co->enough_money = null;
                    }
                }
                $co->save();
                // Save customer
                $customer = $request->input('customer');
                $co->customer()->update($customer);
                // Input relation
                $codes       = $request->input('code');
                $loaiVatLieu = $request->input('loai_vat_lieu');
                $doDay       = $request->input('do_day');
                $tieuChuan   = $request->input('tieu_chuan');
                $kichCo      = $request->input('kich_co');
                $kichThuoc   = $request->input('kich_thuoc');
                $chuanBich   = $request->input('chuan_bich');
                $chuanGasket = $request->input('chuan_gasket');
                $dvTinh      = $request->input('dv_tinh');
                $soLuong     = $request->input('so_luong');
                $soLuongSanXuat = $request->input('so_luong_san_xuat');
                $donGia      = $request->input('don_gia');
                $manufactureType = $request->input('manufacture_type');
                $warehouseGroupId = $request->input('warehouse_group_id');
                $materialType = $request->input('material_type');

                // Detach all warehouse of CO
                $co->warehouses()->delete();
                // Save relation
                foreach ($codes as $key => $code) {
                    $offerPrices[] = [
                        'code'          => $code,
                        'loai_vat_lieu' => $loaiVatLieu[$key],
                        'do_day'        => $doDay[$key],
                        'tieu_chuan'    => $tieuChuan[$key],
                        'kich_co'       => $kichCo[$key],
                        'kich_thuoc'    => $kichThuoc[$key],
                        'chuan_bich'    => $chuanBich[$key],
                        'chuan_gasket'  => $chuanGasket[$key],
                        'dv_tinh'       => $dvTinh[$key],
                        'so_luong'      => $soLuong[$key],
                        'so_luong_san_xuat' => $soLuongSanXuat[$key],
                        'don_gia'       => $donGia[$key],
                        // 'material_type' => in_array($key, $request->input('material_type') ?? [])
                        //     ? OfferPrice::MATERIAL_TYPE_METAL : OfferPrice::MATERIAL_TYPE_NON_METAL
                        'manufacture_type' => $manufactureType[$key],
                        'warehouse_group_id' => $warehouseGroupId[$key],
                        'material_type' => $materialType[$key],
                    ];
                }
                $co->warehouses()->createMany($offerPrices);
                \DB::commit();
                return redirect()->route('admin.co.edit', ['id' => $id])->with('success','Cập nhật CO thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->withInput()->with('error', 'Cập nhật CO thất bại!');
    }

    public function destroy($id)
    {
        $co = $this->coRepository->find($id);
        if ($co) {
            try {
                $contractDocument = json_decode($co->contract_document, true);
                $invoiceDocument  = json_decode($co->invoice_document, true);
                \DB::beginTransaction();
                if ($co->request) {
                    foreach($co->request as $k => $v) {
                        $v->material()->delete();
                    }
                }
                $co->request()->delete();
                $co->request()->detach();
                $co->payment()->delete();
                $co->payment()->detach();
                $co->receipt()->delete();
                $co->receipt()->detach();
                $co->warehouses()->delete();
                $co->delete();
                \DB::commit();
                if ($contractDocument) {
                    foreach($contractDocument as $file) {
                        Storage::disk($this->disk)->delete($file);
                    }
                }
                if ($invoiceDocument) {
                    foreach($invoiceDocument as $file) {
                        Storage::disk($this->disk)->delete($file);
                    }
                }
                return redirect()->route('admin.co.index')->with('success','Xóa CO thành công!');
            } catch(\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
        }
        return redirect()->back()->with('error', 'CO không tồn tại!');
    }

    public function getDataWarehouse(Request $request) {
        $codes  = $request->input('code');
        $result = $this->coService->getProductMaterialsInWarehouses($codes);
        if ($request->ajax()) {
            if ($result->count()) {
                return ['success' => true, 'data' => $result];
            } else {
                return ['success' => true, 'data' => []];
            }
        }
        return $result;
    }

    /*public function getOfferPrice(Request $request) {
        $result = ['success' => false];
        if ($request->has('file')) {
            $aContent = Excel::toArray([], $request->file('file'));
            if (!empty($aContent[0])) {
                unset($aContent[0][0]);    // Remove header
                $warehouses = $aContent[0];
                // Get warehouse
                $codes = [];
                foreach($warehouses as $key => $val) {
                    $codes[] = $val[1];
                }
                $request->request->add(['code' => $codes]);
                $resWarehouse = $this->getDataWarehouse($request);
                if (!$resWarehouse['success']) {
                    return $result;
                }
                $contentMore = view('admins.coes.includes.list-warehouses',['warehouses' => $resWarehouse['data']])->render();
                // Get product
                $content = view('admins.coes.includes.list-products',compact('warehouses'))->render();
                $result  = [
                    'success'      => true,
                    'data'         => $content,
                    'content_more' => $contentMore
                ];
            }
        }
        return $result;
    }*/

    public function getOfferPrice(Request $request) {
        $result = ['success' => false];
        if ($request->has('file')) {
            $aContent = Excel::toArray([], $request->file('file'));
            if (!empty($aContent[0])) {
                $allData    = $aContent[0];
                $warehouses = [];
                $more       = [];
                $isMore     = 0;
                $posShip    = 1;
                foreach($allData as $key => $val) {
                    // ignore: title, row empty
                    if ($key < 7 || ($key >= 14 && $key <= 18)) { // ignore
                        continue;
                    } else if ($key < 14) { // Info customer
                        switch ($key) {
                            case 7:
                                $more['customer[code]'] = $val[2];
                                break;
                            case 8:
                                $more['customer[ten]'] = $val[2];
                                break;
                            case 9:
                                $more['customer[dia_chi]'] = $val[2];
                                break;
                            case 10:
                                $more['customer[mst]'] = $val[2];
                                $more['so_bao_gia']    = $val[10];
                                break;
                            case 11:
                                $more['customer[nguoi_nhan]'] = $val[2];
                                if ($val[10]) {
                                    $dateBG = \Carbon\Carbon::createFromFormat('d/m/Y', $val[10])->format('Y-m-d');
                                } else {
                                    $dateBG = null;
                                }
                                $more['ngay_bao_gia']         = $dateBG;
                            case 12:
                                $more['customer[dien_thoai]'] = $val[2];
                                $more['sales']                = $val[10];
                                break;
                            case 13:
                                $more['customer[email]']  = $val[2];
                                $more['thoi_han_bao_gia'] = $val[10];
                                break;
                        }
                    } else {
                        if($isMore && $isMore >= $key) {
                            continue;
                        } else if (!$isMore && empty($val[1])) {
                            $isMore = $key + 4;
                            continue;
                        }
                        if (!$isMore) {
                            $warehouses[] = $val;
                        } else {
                            // Ship: Get 4 row
                            // Payment: Get 2 row
                            switch ($key - $isMore) {
                                case 1:
                                    $more['dong_goi_va_van_chuyen'] = $val[3];
                                    break;
                                case 2:
                                    $more['noi_giao_hang'] = $val[3];
                                    break;
                                case 3:
                                    $more['xuat_xu'] = $val[3];
                                    break;
                                case 4:
                                    $more['thoi_gian_giao_hang'] = $val[3];
                                    break;
                                case 6:
                                    $valPercent = !empty($val[3]) ? ($val[3] * 100) : null;
                                    $more['thanh_toan[percent][truoc_khi_lam_hang]']        = $valPercent;

                                    $valPercent = !empty($val[4]) ? ($val[4] * 100) : null;
                                    $more['thanh_toan[percent][truoc_khi_giao_hang]']       = $valPercent;

                                    $valPercent = !empty($val[5]) ? ($val[5] * 100) : null;
                                    $more['thanh_toan[percent][ngay_khi_giao_hang]']        = $valPercent;

                                    $valPercent = !empty($val[6]) ? ($val[6] * 100) : null;
                                    $more['thanh_toan[percent][sau_khi_giao_hang_va_cttt]'] = $valPercent;

                                    $valPercent = !empty($val[7]) ? ($val[7] * 100) : null;
                                    $more['thanh_toan[percent][thoi_gian_no]']              = $valPercent;
                                    break;
                                case 7:
                                    $valAmount = !empty($val[3]) ? $val[3] : null;
                                    $more['tmp[amount_money][truoc_khi_lam_hang]']        = $valAmount ? number_format($valAmount) : null;
                                    $more['thanh_toan[amount_money][truoc_khi_lam_hang]'] = $valAmount;

                                    $valAmount = !empty($val[4]) ? $val[4] : null;
                                    $more['tmp[amount_money][truoc_khi_giao_hang]']        = $valAmount ? number_format($valAmount) : null;
                                    $more['thanh_toan[amount_money][truoc_khi_giao_hang]'] = $valAmount;

                                    $valAmount = !empty($val[5]) ? $val[5] : null;
                                    $more['tmp[amount_money][ngay_khi_giao_hang]']        = $valAmount ? number_format($valAmount) : null;
                                    $more['thanh_toan[amount_money][ngay_khi_giao_hang]'] = $valAmount;

                                    $valAmount = !empty($val[6]) ? $val[6] : null;
                                    $more['tmp[amount_money][sau_khi_giao_hang_va_cttt]']        = $valAmount ? number_format($valAmount) : null;
                                    $more['thanh_toan[amount_money][sau_khi_giao_hang_va_cttt]'] = $valAmount;

                                    $valAmount = !empty($val[7]) ? $val[7] : null;
                                    $more['tmp[amount_money][thoi_gian_no]']        = $valAmount ? number_format($valAmount) : null;
                                    $more['thanh_toan[amount_money][thoi_gian_no]'] = $valAmount;
                                    break;
                            }
                            if ($posShip === 7) {
                                break;
                            }
                            $posShip ++;
                        }
                    }
                }
                // Get warehouse
                $codes = [];
                foreach($warehouses as $key => $val) {
                    $codes[] = $val[1];
                }
                $request->request->add(['code' => $codes]);
                $resWarehouse = $this->getDataWarehouse($request);
                if (!$resWarehouse['success']) {
                    return $result;
                }
                // Get material
                $contentMaterial = view('admins.co_tmps.includes.list-warehouses',['warehouses' => $resWarehouse['data']])->render();
                // Get product
                $contentProduct = view('admins.co_tmps.includes.list-products',compact('warehouses'))->render();
                $result  = [
                    'success'      => true,
                    'data'         => $contentProduct,
                    'content_more' => $contentMaterial,
                    'data_more'    => $more,
                ];
            }
        }
        return $result;
    }

    public function getMaterial(Request $request) {
        // Only get warehouse plate
        $content = '';
        $action = $request->query('action', '');
        try {
            $code = $request->input('code');
            $lot_no = $request->input('lot_no', '');
            if ($code) {
                $materials = $this->coService->searchProductMaterialsInWarehouses($code, $lot_no);

                $content   = view('admins.requests.includes.list-materials-full',compact('materials', 'action'))->render();
            }
        } catch(\Exception $ex) {
            dd($ex);
            return [
                'success' => false,
                'content' => $content,
            ];
        }
        return [
            'success' => true,
            'content' => $content,
        ];
    }

    public function saveDelivery(Request $request, $coId)
    {
        try {
            $co = $this->coRepository->find($coId);
            if (!$co) {
                return redirect()->back()->withInput()->with('error','CO không tồn tại!');
            }
            \DB::beginTransaction();
            $input = [
                'shipping_unit'            => $request->input('shipping_unit'),
                'delivery_date'            => $request->input('delivery_date'),
                'shipping_method'          => $request->input('shipping_method'),
                'shipping_fee'             => $request->input('shipping_fee'),
                'status_customer_received' => (int) $request->input('status_customer_received'),
                'co_id'                    => $coId,
            ];
            if ($request->input('id')) {
                $deliveryId = $request->input('id');
                $delivery   = $this->deliveryRepository->find($deliveryId);
                if ($delivery) {
                    $delivery->shipping_unit            = $input['shipping_unit'];
                    $delivery->delivery_date            = $input['delivery_date'];
                    $delivery->shipping_method          = $input['shipping_method'];
                    $delivery->shipping_fee             = $input['shipping_fee'];
                    $delivery->status_customer_received = $input['status_customer_received'];
                    $delivery->save();
                }
            } else {
                $input['admin_id'] = Session::get('login')->id;
                $delivery          = Delivery::create($input);
            }
            // Save timeline
            if ($input['status_customer_received']) {
                $co->start_timeline = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $co->start_timeline = null;
            }
            $co->save();
            \DB::commit();
            return redirect()->back()->with('success','Lưu thông tin Giao Nhận thành công!');
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->withInput()->with('error','Lưu thông tin Giao Nhận thất bại!');
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->coRepository->find($id);
            if ($data) {
                $typeDocument = $request->input('type');
                if ($typeDocument === 'contract_document') {
                    $files = json_decode($data->contract_document, true);
                    if ($files) {
                        $path = $request->input('path');
                        foreach($files as $key => $file) {
                            if ($file['path'] === $path) {
                                Storage::disk($this->disk)->delete($file['path']);
                                unset($files[$key]);
                                $data->contract_document = json_encode(array_values($files));
                                $data->save();
                                return ['success' => true];
                            }
                        }
                    }
                } else if ($typeDocument === 'invoice_document') {
                    $files = json_decode($data->invoice_document, true);
                    if ($files) {
                        $path = $request->input('path');
                        foreach($files as $key => $file) {
                            if ($file['path'] === $path) {
                                Storage::disk($this->disk)->delete($file['path']);
                                unset($files[$key]);
                                $data->invoice_document = json_encode(array_values($files));
                                $data->save();
                                return ['success' => true];
                            }
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
