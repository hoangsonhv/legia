<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Helpers\PermissionHelpers;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestRequest;
use App\Models\Admin;
use App\Models\CoStepHistory;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\SurveyPriceRepository;
use App\Models\Request as RequestModel;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Services\CoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\RequestStepHistoryRepository;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use App\Models\RequestStepHistory;
use App\Models\WarehouseGroup;

class RequestController extends Controller
{
    private $disk = 'local';

    protected $requestRepository;
    protected $coRepository;
    protected $coService;
    protected $surveyPriceRepository;
    protected $coStepHisRepo;

    public $menu;

    function __construct(
        RequestRepository $requestRepository,
        CoRepository $coRepository,
        CoService $coService,
        SurveyPriceRepository $surveyPriceRepository,
        CoStepHistoryRepository $coStepHisRepo
    ) {
        $this->requestRepository     = $requestRepository;
        $this->coRepository          = $coRepository;
        $this->coService             = $coService;
        $this->surveyPriceRepository = $surveyPriceRepository;
        $this->coStepHisRepo         = $coStepHisRepo;
        $this->menu                  = [
            'root' => 'Quản lý Phiếu Yêu Cầu',
            'data' => [
                'parent' => [
                    'href'   => route('admin.request.index'),
                    'label'  => 'Quản lý Phiếu Yêu Cầu'
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
        $statuses                   = ProcessStatus::all();
        $statuses[0]                = 'TẤT CẢ';
        ksort($statuses);

        // Count data pending
        $countPending = $this->requestRepository->getRequests(['used' => 0])->count();

        // search
        if($request->has('used')) {
            $params['used'] = $request->used;
        } else if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
            if ($request->input('status')) {
                $params['status'] = $request->status;
            }
        }

        $arrParms = $request->all();
        $this->requestRepository->setParams($params, $arrParms);

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.request.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $requests   = $this->requestRepository->getRequests($params)->orderBy('id','DESC')->paginate($limit);
        $categories = DataHelper::getCategoriesForIndex();
        $count = [
            $this->requestRepository->countByStatus(),
            $this->requestRepository->countByStatus(ProcessStatus::Pending),
            $this->requestRepository->countByStatus(ProcessStatus::Approved),
            $this->requestRepository->countByStatus(ProcessStatus::Unapproved),
            $this->requestRepository->countByStatus(ProcessStatus::PendingSurveyPrice)
        ];
        $request->flash();
        return view('admins.requests.index',compact('breadcrumb', 'titleForLayout', 'statuses', 'countPending', 'requests', 'categories', 'count'));
    }

    public function create(Request $request, $coId=null)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $steps = \App\Services\CoService::stepCo();

        $coStep = '';
        if ($coId) {
            $categories = DataHelper::getCategories([DataHelper::KHO]);
            $queryCo    = $this->coRepository->getCoes([
                'id'     => $coId,
                'status' => ProcessStatus::Approved
            ])->limit(1);
            $co = $queryCo->first();

            if ($co->currentStep && isset($steps[$co->currentStep->step])) {
                $coStep = $steps[$co->currentStep->step];
            }

            $co = $queryCo->pluck('code', 'id')->toArray();
            if (!$co) {
                return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
            }
            $warehouses    = $queryCo->first()->warehouses;

            $filterWarehouses = $warehouses->filter(function ($warehouse) {
                $detectCode = \App\Helpers\AdminHelper::detectProductCode($warehouse->code);
                $tonKho = \App\Helpers\AdminHelper::countProductMerchanInWarehouse($warehouse->code, $detectCode['model_type']);
		if($detectCode['model_type'] == null) return false;
                if($warehouse->so_luong > $tonKho && $detectCode['manufacture_type'] == WarehouseGroup::TYPE_COMMERCE) {
                    $model = WarehouseHelper::getModel($detectCode['model_type']);
                    $model->setQuantity(0, false);
                    $model->code = $warehouse->code;
                    $model->vat_lieu = $warehouse->loai_vat_lieu;
                    $model->save();
                    return true;
                }
                return false;
            });
            $listWarehouse = $this->coService->getProductMaterialsInWarehouses($queryCo->first()->warehouses->pluck('code')->toArray());
        } else {
            $categories    = DataHelper::getCategories([DataHelper::DINH_KY, DataHelper::VAN_PHONG_PHAM, DataHelper::HOAT_DONG]);
            $co            = array();
            $warehouses    = collect([]);
            $listWarehouse = collect([]);
            $filterWarehouses = collect([]);
        }
        return view('admins.requests.create',compact('steps', 'coStep', 'breadcrumb', 'titleForLayout', 'permissions', 'categories', 'co', 'warehouses','filterWarehouses', 'listWarehouse'));
    }

    public function store(RequestRequest $request)
    {
       try {
            $category = $request->input('category');
            $coId     = $request->input('co_id');
            $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM]);
            if ($coId) {
                $co = $this->coRepository->find($request->input('co_id'));
                if (!$co || $co->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                $coId   = $co->id;
                $coCode = $co->code;
            } else {
                $requireCategory = DataHelper::getCategories([DataHelper::DINH_KY])['Định kỳ'];
                $firstDay = Carbon::now()->firstOfMonth();
                $lastDay = Carbon::now()->lastOfMonth();
                $checkExist = $this->requestRepository->checkExitsCategoryInMonth($category,$firstDay,$lastDay);
                if(in_array($category,array_keys($requireCategory)) && $checkExist){
                    return redirect()->back()->withInput()->with('error','Chỉ có thể tạo một cate trong một tháng!');
                }
                $coId   = null;
                $coCode = null;
            }

            // Upload file
            $files     = $request->file('accompanying_document');
            $documents = [];
            if ($files) {
                $path = 'uploads/requests/accompanying_document';
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
            // dd($request);
            $money_total = 0;

            \DB::beginTransaction();
            $inputMaterials = $request->input('material');
            $baseWarehouseRepository = new BaseWarehouseRepository();
            $baseWarehouseRepository->setModel(WarehouseHelper::getModel(WarehouseHelper::KHO_VAT_DUNG));

            foreach($inputMaterials['code'] as $key => $code) {
                if (empty($code) || !$inputMaterials['dinh_luong'][$key]) {
                    continue;
                }
                if(in_array($category, array_keys($categories))) {
                    $wh = [
                        'code'          => $inputMaterials['code'][$key],
                        'mo_ta'         => $inputMaterials['mo_ta'][$key],
                        'dvt'           => $inputMaterials['dv_tinh'][$key],
                        'ghi_chu'       => $inputMaterials['ghi_chu'][$key],
                        'model_type'    => WarehouseHelper::KHO_VAT_DUNG,
                        'quantity'      => 0,
                    ];
                    $supply = $baseWarehouseRepository->create($wh, false);
                    $materials[] = [
                        'merchandise_id'=> $supply->l_id,
                        'code'          => $inputMaterials['code'][$key],
                        'mo_ta'         => $inputMaterials['mo_ta'][$key],
                        'kich_thuoc'    => isset($inputMaterials['kich_thuoc']) ? $inputMaterials['kich_thuoc'][$key] : null,
                        'quy_cach'      => isset($inputMaterials['quy_cach']) ? $inputMaterials['quy_cach'][$key] : null,
                        'dv_tinh'       => $inputMaterials['dv_tinh'][$key],
                        'dinh_luong'    => $inputMaterials['dinh_luong'][$key],
                        'thoi_gian_can' => $inputMaterials['thoi_gian_can'][$key],
                        'don_gia'       => isset($inputMaterials['don_gia']) ? $inputMaterials['don_gia'][$key] : 0,
                        'thanh_tien'    => isset($inputMaterials['thanh_tien']) ? $inputMaterials['thanh_tien'][$key] : 0,
                        'ghi_chu'       => $inputMaterials['ghi_chu'][$key],
                    ];
                } else {
                    $materials[] = [
                        'merchandise_id'=> $inputMaterials['merchandise_id'][$key],
                        'code'          => $inputMaterials['code'][$key],
                        'mo_ta'         => $inputMaterials['mo_ta'][$key],
                        'kich_thuoc'    => isset($inputMaterials['kich_thuoc']) ? $inputMaterials['kich_thuoc'][$key] : null,
                        'quy_cach'      => isset($inputMaterials['quy_cach']) ? $inputMaterials['quy_cach'][$key] : null,
                        'dv_tinh'       => $inputMaterials['dv_tinh'][$key],
                        'dinh_luong'    => $inputMaterials['dinh_luong'][$key],
                        'thoi_gian_can' => $inputMaterials['thoi_gian_can'][$key],
                        'don_gia'       => isset($inputMaterials['don_gia']) ? $inputMaterials['don_gia'][$key] : 0,
                        'thanh_tien'    => isset($inputMaterials['thanh_tien']) ? $inputMaterials['thanh_tien'][$key] : 0,
                        'ghi_chu'       => $inputMaterials['ghi_chu'][$key],
                    ];
                }
                $money_total += (isset($inputMaterials['thanh_tien']) ? $inputMaterials['thanh_tien'][$key] : 0);
            }
            $input = [
                'co_id'                 => $coId,
                'co_code'               => $coCode,
                'category'              => $category,
                'code'                  => $this->requestRepository->getIdCurrent(),
                'admin_id'              => Session::get('login')->id,
                'note'                  => $request->input('note'),
                'money_total'           => $money_total ?? null,
                'thanh_toan'            => null,
                'accompanying_document' => json_encode($documents)
            ];
            if(in_array($category, array_keys(DataHelper::getCategoriesForIndex([DataHelper::DINH_KY, DataHelper::HOAT_DONG])))) {
                $thanh_toan = [
                    "percent" => [
                        "truoc_khi_lam_hang" => null,
                        "truoc_khi_giao_hang" => null,
                        "ngay_khi_giao_hang" => null,
                        "sau_khi_giao_hang_va_cttt" => "100",
                        "thoi_gian_no" => null,
                    ],
                    "amount_money" => [
                        "truoc_khi_lam_hang" => null,
                        "truoc_khi_giao_hang" => null,
                        "ngay_khi_giao_hang" => null,
                        "sau_khi_giao_hang_va_cttt" => $input['money_total'],
                        "thoi_gian_no" => null,
                    ],
                    "payment_document" => [
                        "file_hoa_don" => [],
                        "file_phieu_xuat_kho" => [],
                        "file_bien_ban_ban_giao" => [],
                        "file_bien_ban_nghiem_thu" => [],
                        "file_giay_bao_hanh" => [],
                        "file_chung_nhan_xuat_xuong" => [],
                        "file_chung_chi_xuat_xu_vat_lieu" => [],
                        "file_chung_chi_chat_luong_vat_lieu" => [],
                        "file_test_report_vat_lieu" => [],
                        "file_test_report_thuc_pham" => [],
                    ],
                ];
                $input['thanh_toan'] = $thanh_toan;
            }
            // Save request
            $requestModel = RequestModel::create($input);
            // Save co_step_history
            if($request->input('co_id')) {
                $this->coStepHisRepo->insertNextStep( 'request', $request->input('co_id'), $requestModel->id, CoStepHistory::ACTION_APPROVE);
            }
            // Save relationship
            if (!empty($materials)) {
                $requestModel->material()->createMany($materials);
                if (!empty($co)) {
                    $requestModel->co()->save($co);
                    $co->used = 1;
                    $co->save();
                }
                \DB::commit();
                return redirect()->route('admin.request.index')->with('success','Tạo Phiếu Yêu Cầu thành công!');
            }
       } catch(\Exception $ex) {
           \DB::rollback();
           dd($ex);
       }
        return redirect()->route('admin.request.index')->with('error','Tạo Phiếu Yêu Cầu thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $requestModel               = $this->requestRepository->find($id);
        $corePriceSurvey            = AdminHelper::getCorePriceSurvey();
        $steps = \App\Services\CoService::stepCo();

        if ($requestModel) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.request.index-all') && $requestModel->admin_id != $user->id) {
                return redirect()->route('admin.request.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions = config('permission.permissions');
            $existsCat   = 0;
            $totalPayment = 0;
            $canCreatePayment = false;
            $canCreateWarehouseReceipt = false;
            $coStep = '';

            if ($requestModel->co_id) {
                $categories = DataHelper::getCategories([DataHelper::KHO]);
                $queryCo    = $this->coRepository->getCoes([
                    'id'     => $requestModel->co_id,
                    'status' => ProcessStatus::Approved
                ])->limit(1);

                $co = $queryCo->first();

                if ($co->currentStep && isset($steps[$co->currentStep->step])) {
                    $coStep = $steps[$co->currentStep->step];
                }

                $coModel = $queryCo->first();
                $co = $queryCo->pluck('code', 'id')->toArray();
                if (!$co) {
                    return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
                }
                $warehouses    = $queryCo->first()->warehouses;
                $listWarehouse = $this->coService->getProductMaterialsInWarehouses($queryCo->first()->warehouses->pluck('code', 'id')->toArray());

                if($coModel->currentStep) {
                    $canCreatePayment = in_array($coModel->currentStep->step, [
                            CoStepHistory::STEP_CREATE_PAYMENT_N1,
                            CoStepHistory::STEP_CREATE_PAYMENT_N2,
                            CoStepHistory::STEP_CREATE_PAYMENT_N3,
                            CoStepHistory::STEP_CREATE_PAYMENT_N4,
                        ]);
                    $canCreateWarehouseReceipt = $coModel->currentStep->step == CoStepHistory::STEP_CREATE_WAREHOUSE_RECEIPT;
                }
            } else {
                $categories    = DataHelper::getCategories([DataHelper::DINH_KY, DataHelper::VAN_PHONG_PHAM, DataHelper::HOAT_DONG]);
                $co            = array();
                $warehouses    = collect([]);
                $listWarehouse = collect([]);
                // if (!$requestModel->payments->count() && in_array($requestModel->category,array_keys( DataHelper::getCategoriesForIndex([DataHelper::DINH_KY, DataHelper::HOAT_DONG])))) {
                //     $canCreatePayment = true;
                // } else if(in_array($requestModel->category,array_keys( DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM])))) {
                    // dd($requestModel->currentStep->step);
                $currentStep = $requestModel->currentStep;
                if($currentStep) {
                    $canCreatePayment = in_array($currentStep->step, [
                        CoStepHistory::STEP_CREATE_PAYMENT_N1,
                        CoStepHistory::STEP_CREATE_PAYMENT_N2,
                        CoStepHistory::STEP_CREATE_PAYMENT_N3,
                        CoStepHistory::STEP_CREATE_PAYMENT_N4,
                    ]);
                    $canCreateWarehouseReceipt = $currentStep->step == CoStepHistory::STEP_CREATE_WAREHOUSE_RECEIPT;
                }
                // }
            }

            $materials = $requestModel->material;
            $materialsCount = count($materials);
            $selectedPriceSurveyCount = 0;
            foreach ($materials as $material) {
                $selectedPriceSurvey = $material->price_survey()->where('status', '1')->first();
                if ($selectedPriceSurvey != null) {
                    $totalPayment += $selectedPriceSurvey->price;
                    $selectedPriceSurveyCount++;
                }
            }
            $isSelectedPriceSurveyForAllMaterials = $selectedPriceSurveyCount == $materialsCount;

            $arrPayments = $requestModel->payments()->get()->toArray();
            $payments = [];
            foreach ($arrPayments as $recod) {
                $payments[$recod['step_id']] = $recod;
            }
            return view('admins.requests.edit',compact('totalPayment', 'coStep', 'steps', 'breadcrumb', 'titleForLayout', 'requestModel',
                'permissions', 'categories', 'co', 'materials', 'existsCat', 'warehouses', 'listWarehouse',
                'corePriceSurvey', 'payments', 'isSelectedPriceSurveyForAllMaterials', 'canCreatePayment', 'canCreateWarehouseReceipt'));
        }
        return redirect()->route('admin.request.index')->with('error', 'Phiếu Yêu Cầu không tồn tại!');
    }

    public function update(RequestRequest $request, $id)
    {
//        try {
            $category = $request->input('category');
            $coId     = $request->input('co_id');
            if ($coId) {
                $co = $this->coRepository->find($request->input('co_id'));
                if (!$co || $co->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                $coId   = $co->id;
                $coCode = $co->code;
            } else {
                $coId   = null;
                $coCode = null;
            }
            $requestModel = $this->requestRepository->find($id);
            if (!empty($request['is_request_payment'])) {
                $thanhToanModel = $requestModel->thanh_toan;
                $paymentDocumentModels = isset($thanhToanModel['payment_document']) ? $thanhToanModel['payment_document'] : [];
                $thanhToan = $request->thanh_toan;
                $keyPaymentDocuments = CoService::paymentDocuments();
                foreach ($keyPaymentDocuments as $key => $text) {
                    $arrDoc = [];
                    if(isset($paymentDocumentModels['file_'. $key])) {
                        $arrDoc = $paymentDocumentModels['file_'. $key];
                    }
                    $fileDoc = $request->file('thanh_toan.payment_document.file_'. $key);
                    if($fileDoc) {
                        $path = 'uploads/requests/thanh_toan/documents';
                        foreach($fileDoc as $file) {
                            $fileSave = Storage::disk($this->disk)->put($path, $file);
                            if (!$fileSave) {
                                if ($arrDoc) {
                                    foreach($arrDoc as $doc) {
                                        Storage::disk($this->disk)->delete($doc);
                                    }
                                }
                            }
                            $arrDoc[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                        }
                    }
                    $thanhToan['payment_document']['file_'. $key] = $arrDoc;
                }
                $request->merge([
                    'thanh_toan' => $thanhToan,
                ]);
                $requestModel->thanh_toan            = $request->input('thanh_toan');
                $requestModel->money_total           = $request->input('money_total');
                $requestModel->save();
                return redirect()->route('admin.request.edit', ['id' => $id])->with('success','Cập nhật Phiếu Yêu Cầu thành công!');
            }
            if ($requestModel) {
                $files     = $request->file('accompanying_document');
                $documents = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/requests/accompanying_document';
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
                $documents = array_merge(json_decode($requestModel->accompanying_document, true), $documents);

                if($request->has('thanh_toan') && $request->thanh_toan) {
                    $thanhToanModel = $requestModel->thanh_toan;
                    $paymentDocumentModels = isset($thanhToanModel['payment_document']) ? $thanhToanModel['payment_document'] : [];
                    $thanhToan = $request->thanh_toan;
                    $keyPaymentDocuments = CoService::paymentDocuments();
                    foreach ($keyPaymentDocuments as $key => $text) {
                        $arrDoc = [];
                        if(isset($paymentDocumentModels['file_'. $key])) {
                            $arrDoc = $paymentDocumentModels['file_'. $key];
                        }
                        $fileDoc = $request->file('thanh_toan.payment_document.file_'. $key);
                        if($fileDoc) {
                            $path = 'uploads/requests/thanh_toan/documents';
                            foreach($fileDoc as $file) {
                                $fileSave = Storage::disk($this->disk)->put($path, $file);
                                if (!$fileSave) {
                                    if ($arrDoc) {
                                        foreach($arrDoc as $doc) {
                                            Storage::disk($this->disk)->delete($doc);
                                        }
                                    }
                                }
                                $arrDoc[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                            }
                        }
                        $thanhToan['payment_document']['file_'. $key] = $arrDoc;
                    }
                    $request->merge([
                        'thanh_toan' => $thanhToan,
                    ]);
                }
                \DB::beginTransaction();
                $money_total = 0;
                $inputMaterials = $request->input('material');
                foreach($inputMaterials['code'] as $key => $code) {
                    if (empty($code) || !$inputMaterials['dinh_luong'][$key] || ($coId && !$inputMaterials['merchandise_id'][$key])) {
                        continue;
                    }
                    $materials[] = [
                        'merchandise_id'=> $inputMaterials['merchandise_id'][$key],
                        'code'          => $inputMaterials['code'][$key],
                        'mo_ta'         => $inputMaterials['mo_ta'][$key],
                        'kich_thuoc'    => isset($inputMaterials['kich_thuoc']) ? $inputMaterials['kich_thuoc'][$key] : null,
                        'quy_cach'      => isset($inputMaterials['quy_cach']) ? $inputMaterials['quy_cach'][$key] : null,
                        'dv_tinh'       => $inputMaterials['dv_tinh'][$key],
                        'dinh_luong'    => $inputMaterials['dinh_luong'][$key],
                        'don_gia'       => isset($inputMaterials['don_gia']) ? $inputMaterials['don_gia'][$key] : 0,
                        'thanh_tien'    => isset($inputMaterials['thanh_tien']) ? $inputMaterials['thanh_tien'][$key] : 0,
                        'thoi_gian_can' => $inputMaterials['thoi_gian_can'][$key],
                        'ghi_chu'       => $inputMaterials['ghi_chu'][$key],
                    ];
                    $money_total += (isset($inputMaterials['thanh_tien']) ? $inputMaterials['thanh_tien'][$key] : 0);

                }
                // Save request
                $requestModel->co_id                 = $coId;
                $requestModel->co_code               = $coCode;
                $requestModel->category              = $category;
                $requestModel->note                  = $request->input('note');
                $requestModel->accompanying_document = json_encode($documents);
                $requestModel->thanh_toan            = $request->input('thanh_toan');
                $requestModel->money_total           = $coId ? $request->input('money_total') : $money_total;
                $requestModel->save();
                // Save relationship
                $requestModel->material()->delete();
                if (!empty($materials)) {
                    $requestModel->material()->createMany($materials);

                    /*$requestModel->co()->detach();
                    if (!empty($co)) {
                        $requestModel->co()->sync($co);
                    }*/
                    \DB::commit();
                    return redirect()->route('admin.request.edit', ['id' => $id])->with('success','Cập nhật Phiếu Yêu Cầu thành công!');
                }
            }
//        } catch(\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->back()->with('error', 'Cập nhật Phiếu Yêu Cầu thất bại!');
    }

    public function destroy($id)
    {
        try {
            $request = $this->requestRepository->find($id);
            if ($request) {
                $files = json_decode($request->accompanying_document, true);
                \DB::beginTransaction();
                $request->material()->delete();
                $request->co()->detach();
                $request->delete();
                \DB::commit();
                if ($files) {
                    foreach($files as $file) {
                        Storage::disk($this->disk)->delete($file);
                    }
                }
                return redirect()->route('admin.request.index')->with('success','Xóa Phiếu Yêu Cầu thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->with('error', 'Phiếu Yêu Cầu không tồn tại!');
    }

    public function getCo(Request $request) {
        $keyword = $request->input('keyword_co');
        if (!$keyword) {
            $co = $this->coRepository->getCoes()->orderBy('id','DESC')->limit('10');
        } else {
            $co = $this->coRepository->getCoes([
                'code' => ['code', 'like', $keyword]
            ])->orderBy('id','DESC')->limit('10');
        }
        return $co->pluck('code', 'id')->toArray();
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->requestRepository->find($id);
            if ($data) {
                $files = json_decode($data->accompanying_document, true);
                if ($files) {
                    $path = $request->input('path');
                    foreach($files as $key => $file) {
                        if ($file['path'] === $path) {
                            Storage::disk($this->disk)->delete($file['path']);
                            unset($files[$key]);
                            $data->accompanying_document = json_encode(array_values($files));
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

    public function updateSurveyPrice(Request $request, $id)
    {
        // dd($request->all());
//        try {
//            \DB::beginTransaction();
            $requestModel = $this->requestRepository->find($id);
            if ($requestModel) {
                $ids   = [];
                $input = $request->survey_price;
                foreach($input as $key => $value) {
                    if (!empty($value['id'])) {
                        $surveyPrice = $this->surveyPriceRepository->find($value['id']);
                        if ($surveyPrice) {
                            $ids[]  = $surveyPrice->id;
                            $data[] = [
                                'accompanying_document' => $surveyPrice->accompanying_document,
                                'is_accept'             => isset($value['is_accept']) ? $value['is_accept'] : false,
                                'note'                  => $value['note'],
                                'core_price_survey_id'  => isset($value['core_price_survey_id']) ? $value['core_price_survey_id'] : $surveyPrice->core_price_survey_id,
                                'status'                => isset($value['status']) ? \App\Models\PriceSurvey::TYPE_BUY : \App\Models\PriceSurvey::TYPE_FAIL,
                            ];
                        }
                    } elseif (!empty($value['accompanying_document'])) {
                        $files     = $value['accompanying_document'];
                        $documents = [];
                        // Upload file
                        if ($files) {
                            $path = 'uploads/requests/survey_prices/accompanying_document';
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
                        $data[] = [
                            'accompanying_document' => json_encode($documents),
                            'is_accept'             => isset($value['is_accept']) ? $value['is_accept'] : false,
                            'note'                  => $value['note'],
                            'core_price_survey_id'     => $value['core_price_survey_id'],
                        ];
                    }else {
                        $data[] = [
                            'accompanying_document' => json_encode([]),
                            'is_accept'             => isset($value['is_accept']) ? $value['is_accept'] : false,
                            'note'                  => $value['note'],
                            'core_price_survey_id'     => $value['core_price_survey_id'],
                        ];
                    }
                }
                if (!empty($data)) {
                    if ($requestModel->surveyPrices->count()) {
                        $oldFiles = [];
                        foreach($requestModel->surveyPrices as $key => $value) {
                            if (!in_array($value->id, $ids) && $value->accompanying_document) {
                                $oldFiles = array_merge($oldFiles, json_decode($value->accompanying_document, true));
                            }
                        }
                        $requestModel->surveyPrices()->delete();
                    }
                    $requestModel->surveyPrices()->createMany($data);
                    \DB::commit();
                    if (!empty($oldFiles)) {
                        foreach($oldFiles as $key => $file) {
                            Storage::disk($this->disk)->delete($file);
                        }
                    }
                    return redirect()->route('admin.request.edit', ['id' => $id])->with('success','Cập nhật Khảo Sát Giá thành công!');
                } else {
                    return redirect()->back()->with('error', 'Chứng từ Khảo Sát Giá không tồn tại!');
                }
            }
//        } catch(\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->back()->with('error', 'Cập nhật Khảo Sát Giá thất bại!');
    }

    public function removeFileSurveyPrice(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->surveyPriceRepository->find($id);
            if ($data) {
                $files = json_decode($data->accompanying_document, true);
                if ($files && count($files) > 1) {
                    $path = $request->input('path');
                    foreach($files as $key => $file) {
                        if ($file['path'] === $path) {
                            Storage::disk($this->disk)->delete($file['path']);
                            unset($files[$key]);
                            $data->accompanying_document = json_encode(array_values($files));
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

    public function removeFilePaymentDocument(Request $request)
    {
//        try {
            $id = $request->input('id');
            $requestModel = $this->requestRepository->find($id);
            if ($requestModel) {
                $thanhToanModel = $requestModel->thanh_toan;
                $paymentDocumentModels = $thanhToanModel['payment_document'];
                $key = $request->input('key');
                if(!empty($paymentDocumentModels[$key])) {
                    $files = $paymentDocumentModels[$key];
                    if ($files && count($files)) {
                        $path = $request->input('path');
                        foreach($files as $keyFile => $file) {
                            if ($file['path'] === $path) {
                                Storage::disk($this->disk)->delete($file['path']);
                                unset($files[$keyFile]);
                                $thanhToanModel['payment_document'][$key] = array_values($files);
                                $requestModel->thanh_toan = $thanhToanModel;
                                $requestModel->save();
                                return ['success' => true];
                            }
                        }
                    }
                }

            }
//        } catch(\Exception $ex) {
//            report($ex);
//        }
//        return ['success' => false];
    }
}
