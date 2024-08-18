<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\PriceSurveyRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\Repositories\RequestStepHistoryRepository;
use App\Models\Request as RequestModel;
use App\Models\CoreCustomer;
use App\Models\PriceSurvey;
use Illuminate\Http\Request;
use App\Models\Repositories\PriceSurveyRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\CoStepHistory;
use Illuminate\Support\Facades\Session;

class PriceSurveyController extends Controller
{
    protected $bankRepository;
    protected $bankHistoryTransactionRepository;
    protected $cusRepo;
    protected $coRepo;
    protected $configRepo;
    protected $priceSurveyRepo;
    protected $coStepHisRepo;
    protected $requestHisRepo;

    public $menu;

    function __construct(
        PriceSurveyRepository $priceSurveyRepo,
        CoStepHistoryRepository $coStepHisRepo,
        RequestStepHistoryRepository $requestHisRepo)
    {
        $this->priceSurveyRepo                  = $priceSurveyRepo;
        $this->coStepHisRepo         = $coStepHisRepo;
        $this->requestHisRepo = $requestHisRepo;
        $this->menu                             = [
            'root' => 'Quản lý Khảo sát giá',
            'data' => [
                'parent' => [
                    'href'   => route('admin.price-survey.index'),
                    'label'  => 'Khảo sát giá'
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
        $types                      = PriceSurvey::ARR_TYPE;
        $coreCustomers              = AdminHelper::getCoreCustomer();

        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        if ($request->input('core_customer_id')) {
            $params['core_customer_id'] = $request->core_customer_id;
        }

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.price-survey.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $datas = $this->priceSurveyRepo->search($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.price_survey.index',compact('breadcrumb', 'titleForLayout', 'datas', 'types',
            'coreCustomers'));
    }

    public function create(Request $request)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $types                      = PriceSurvey::ARR_TYPE;
        $status                     = PriceSurvey::ARR_STATUS;

        $inputs = $request->input();
        $co = isset($inputs['co_id']) ? Co::find($inputs['co_id']) : null;
        $request = isset($inputs['request_id']) ? RequestModel::find($inputs['request_id']) : null;
        if(!$co || !$request) {
            return redirect()->back()->withInput()->with('error', 'Không tìm thấy CO hoặc phiếu yêu cầu!');
        }

        $arrCo[$co->id] = $co->code;
        $arrRequest[$request->id] = $request->code;

        return view('admins.price_survey.create',compact('breadcrumb', 'titleForLayout', 'permissions',
            'types', 'status', 'arrRequest', 'arrCo'));
    }

    public function store(Request $request)
    {
        $input = $request->input();
        $input['admin_id'] = Session::get('login')->id;
        $priceSurvey  = PriceSurvey::create($input);
        if ($priceSurvey) {
            return redirect()->route('admin.price-survey.index')->with('success','Tạo khảo sát giá thành công!');
        }
        return redirect()->route('admin.price-survey.index')->with('error','Tạo khảo sát giá thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $model                      = $this->priceSurveyRepo->find($id);
        $types                      = PriceSurvey::ARR_TYPE;
        $status                     = PriceSurvey::ARR_STATUS;

        if ($model) {
            $permissions             = config('permission.permissions');

            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.price-survey.index-all') && $model->admin_id != $user->id) {
                return redirect()->route('admin.price-survey.index')->with('error', 'Bạn không có quyền truy cập!');
            }

            $co = Co::find($model->co_id);
            $request = RequestModel::find($model->request_id);
            if(!$co || !$request) {
                return redirect()->back()->withInput()->with('error', 'Không tìm thấy CO hoặc phiếu yêu cầu!');
            }
            $arrCo[$co->id] = $co->code;
            $arrRequest[$request->id] = $request->code;

            return view('admins.price_survey.edit',compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'types', 'status', 'arrCo', 'arrRequest'));
        }
        return redirect()->route('admin.price-survey.index')->with('error', 'Khảo sát giá không tồn tại!');
    }

    public function update(PriceSurveyRequest $request, $id)
    {
        $model = $this->priceSurveyRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            $result = $this->priceSurveyRepo->update($inputs, $id);
            return redirect()->route('admin.price-survey.edit', ['id' => $id])->with('success','Cập nhật khảo sát giá thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Khảo sát giá không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->priceSurveyRepo->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.price-survey.index')->with('success','Xóa khảo sát giá thành công!');
        }
        return redirect()->back()->with('error', 'Khảo sát giá không tồn tại!');
    }

    public function insertMultiple(Request $request)
    {
        $ids = $request->input('id');
        if ($ids == null) {
            return redirect()->back()->with('error', 'Vui lòng nhập khảo sát giá!');
        }

        $supplier = $request->input('supplier');
        $type = $request->input('type');
        $productGroup = $request->input('product_group');
        $deadline = $request->input('deadline');
        $price = $request->input('price');
        $numberDateWaitPay = $request->input('number_date_wait_pay');
        $status = $request->input('status');
        $requestId = $request->input('request_id');
        $materialId = $request->input('material_id');
        $coId = $request->input('co_id');
        $user = Session::get('login');
        $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM]);

        $dataInsert = [];
        foreach ($ids as $key => $id) {
            $data = [
                'supplier' => $supplier[$key],
                'type' => 1,
                'product_group' => '',
                'deadline' => $deadline[$key],
                'price' => $price[$key],
                'number_date_wait_pay' => $numberDateWaitPay[$key],
                'admin_id' => $user->id,
                'request_id' => $requestId,
                'material_id' => $materialId,
                'co_id' => $coId,
                'status' => (isset($status[$key]) && $status[$key]) ? $status[$key] : 0,
            ];
            if(!$ids[$key]) {
                $dataInsert[] = $data;
            } else {
                PriceSurvey::where('id', $ids[$key])->update($data);
            }
        }
        $priceSurvey  = PriceSurvey::insert($dataInsert);


        if ($priceSurvey) {
            $requestModel = RequestModel::find($requestId);
            if ($requestModel->co_id != null || in_array($requestModel->category, array_keys($categories))) {
                // Check if all request materials have checked price survey
                $materials = $requestModel->material;
                $materialsCount = count($materials);
                $selectedPriceSurveyCount = 0;
                foreach ($materials as $material) {
                    $selectedPriceSurvey = $material->price_survey()->where('status', '1')->first();
                    if ($selectedPriceSurvey != null) {
                        $selectedPriceSurveyCount++;
                    }
                }
                if ($selectedPriceSurveyCount == $materialsCount) {
                    if($requestModel->co_id) {
                        $this->coStepHisRepo->insertNextStep( 'request', $requestModel->co_id, $requestModel->id, CoStepHistory::ACTION_APPROVE_PRICE_SURVEY);
                    } else if (in_array($requestModel->category, array_keys($categories))) {
                        $this->requestHisRepo->insertNextStep( 'request', $requestModel->id, $requestModel->id, CoStepHistory::ACTION_APPROVE_PRICE_SURVEY);
                    }
                }
            }
            return redirect()->route('admin.request.edit', ['id' => $requestId])->with('success','Thêm khảo sát giá thành công!');
        }
    }
}
