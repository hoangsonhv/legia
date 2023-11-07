<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoTmpRequest;
use App\Models\Admin;
use App\Models\CoreCustomer;
use App\Models\CoTmp;
use App\Models\Import\ChaoGiaImport;
use App\Models\Repositories\CoTmpRepository;
use App\Models\Repositories\ConfigRepository;
use App\Services\CoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Facades\Excel;

class CoTmpController extends Controller
{
    protected $coRepository;
    protected $coService;
    protected $configRepository;

    public $menu;

    function __construct(
        CoTmpRepository $coRepository,
        CoService $coService,
        ConfigRepository $configRepository
    ) {
        $this->coRepository     = $coRepository;
        $this->coService        = $coService;
        $this->configRepository = $configRepository;
        $this->menu             = [
            'root' => 'Quản lý Chào Giá',
            'data' => [
                'parent' => [
                    'href'   => route('admin.co-tmp.index'),
                    'label'  => 'Quản lý Chào Giá'
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
        $cores                      = CoreCustomer::all();
        $statuses[0]                = 'Chọn trạng thái';
        ksort($statuses);

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
            if ($request->input('core_customer_id')) {
                $params['core_customer_id'] = $request->core_customer_id;
            }
        }

        $arrParms = $request->all();
        $this->coRepository->setParams($params, $arrParms);

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.co-tmp.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $coes            = $this->coRepository->getCoes($params)->orderBy('id','DESC')->paginate($limit);
        $limitApprovalCg = $this->configRepository->getConfigs(['key' => 'limit_approval_cg'])->first()->value;
        $request->flash();
        return view('admins.co_tmps.index',compact('breadcrumb', 'titleForLayout', 'statuses', 'countPending',
            'coes', 'limitApprovalCg', 'coreCustomers'));
    }

    public function create()
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.co_tmps.create',compact('breadcrumb', 'titleForLayout', 'permissions'));
    }

    public function store(CoTmpRequest $request)
    {
        // try {
            // \DB::beginTransaction();
            $customer = $request->input('customer');
            // Save core customer
            $dataCoreCustomer = [
                'code' => $customer['code'],
                'name' => $customer['ten'],
                'tax_code' => $customer['mst'],
                'address' => $customer['dia_chi'],
                'phone' => $customer['dien_thoai'],
                'email' => $customer['email'],
                'recipient' => $customer['nguoi_nhan'],
            ];
            $coreCustomer = CoreCustomer::where(['code' => $dataCoreCustomer['code']])->first();
            if(!$coreCustomer) {
                $coreCustomer = CoreCustomer::create($dataCoreCustomer);
            }

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
                'core_customer_id'       => $coreCustomer ? $coreCustomer->id : null
            ];
            $co = CoTmp::create($input);
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
                    'manufacture_type' => $manufactureType[$key],
                    'warehouse_group_id' => $warehouseGroupId[$key],
                    'material_type' => $materialType[$key],
                ];
            }
            $co->warehouses()->createMany($offerPrices);
            \DB::commit();
            return redirect()->route('admin.co-tmp.index')->with('success','Tạo Chào Giá thành công!');
        // } catch(\Exception $ex) {
        //     \DB::rollback();
        //     report($ex);
        // }
        // return redirect()->back()->withInput()->with('error','Tạo Chào Giá thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];

        $co       = $this->coRepository->find($id);
        if ($co) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.co-tmp.index-all') && $co->admin_id != $user->id) {
                return redirect()->route('admin.co-tmp.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $warehouses      = $co->warehouses;
            $permissions     = config('permission.permissions');
            $limitApprovalCg = $this->configRepository->getConfigs(['key' => 'limit_approval_cg'])->first()->value;
            return view('admins.co_tmps.edit',compact('breadcrumb', 'titleForLayout', 'co', 'permissions', 'warehouses', 'limitApprovalCg'));
        }
        return redirect()->route('admin.co-tmp.index')->with('error', 'Chào Giá không tồn tại!');
    }

    public function update(CoTmpRequest $request, $id)
    {
//        try {
            $co = $this->coRepository->find($id);
            if ($co) {
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
                $donGia      = $request->input('don_gia');
                $manufactureType = $request->input('manufacture_type');
                $warehouseGroupId = $request->input('warehouse_group_id');
                $materialType = $request->input('material_type');
                // Detach all warehouse of Chào Giá
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
                        'don_gia'       => $donGia[$key],
                        'manufacture_type' => $manufactureType[$key],
                        'warehouse_group_id' => $warehouseGroupId[$key],
                        'material_type' => $materialType[$key],
                    ];
                }
                $co->warehouses()->createMany($offerPrices);
                \DB::commit();
                return redirect()->route('admin.co-tmp.edit', ['id' => $id])->with('success','Cập nhật Chào Giá thành công!');
            }
//        } catch(\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->back()->withInput()->with('error', 'Cập nhật Chào Giá thất bại!');
    }

    public function destroy($id)
    {
        $co = $this->coRepository->find($id);
        if ($co) {
            try {
                \DB::beginTransaction();
                if ($co->request) {
                    foreach($co->request as $k => $v) {
                        $v->material()->delete();
                    }
                }
                $co->warehouses()->delete();
                $co->delete();
                \DB::commit();
                return redirect()->route('admin.co-tmp.index')->with('success','Xóa Chào Giá thành công!');
            } catch(\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
        }
        return redirect()->back()->with('error', 'Chào Giá không tồn tại!');
    }

    public function getDataWarehouse(Request $request) {
        $codes  = $request->input('code');
        $result = $this->coService->getProductMaterialsInWarehouses($codes, true);
        if ($request->ajax()) {
            if ($result->count()) {
                return ['success' => true, 'data' => $result];
            } else {
                return ['success' => true, 'data' => []];
            }
        }
        return $result;
    }

    public function getDataInAllWarehouse(Request $request)
    {
        $codes  = $request->input('code');
        $result = $this->coService->getProductMaterialsInWarehouses($codes, true);
        if ($request->ajax()) {
            if ($result->count()) {
                return ['success' => true, 'data' => $result];
            } else {
                return ['success' => true, 'data' => []];
            }
        }
        return $result;
    }

    public function getOfferPrice(Request $request) {
        $result = ['success' => false];
        if ($request->has('file')) {
            $aContent = Excel::toArray(new ChaoGiaImport(), $request->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);
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
                                break;
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
                                    $valPercent = !empty($val[3]) ? ($val[3] * 100) . '%' : null;
                                    $more['thanh_toan[percent][truoc_khi_lam_hang]']        = $valPercent;

                                    $valPercent = !empty($val[4]) ? ($val[4] * 100) . '%' : null;
                                    $more['thanh_toan[percent][truoc_khi_giao_hang]']       = $valPercent;

                                    $valPercent = !empty($val[5]) ? ($val[5] * 100) . '%' : null;
                                    $more['thanh_toan[percent][ngay_khi_giao_hang]']        = $valPercent;

                                    $valPercent = !empty($val[6]) ? ($val[6] * 100) . '%' : null;
                                    $more['thanh_toan[percent][sau_khi_giao_hang_va_cttt]'] = $valPercent;

                                    $valPercent = !empty($val[7]) ? ($val[7] * 100) . '%' : null;
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
//                $resWarehouse = $this->getDataWarehouse($request);
                $resWarehouse = $this->getDataInAllWarehouse($request);
                if (!$resWarehouse['success']) {
                    return $result;
                }
                // dd($warehouses);
                $resWarehouse['data'] = $resWarehouse['data'];
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
}
