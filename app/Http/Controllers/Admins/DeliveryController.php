<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRequest;
use App\Models\Admin;
use App\Models\CoStepHistory;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Models\Repositories\DeliveryRepository;
use App\Models\Repositories\CoRepository;
use App\Enums\ProcessStatus;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\CoStepHistoryRepository;

class DeliveryController extends Controller
{
    /**
     * @var
     */
    protected $deliRepo;

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

    /**
     * DeliveryController constructor.
     * @param DeliveryRepository $deliRepo
     */
    function __construct(DeliveryRepository $deliRepo,
                    CoRepository $coRepo,
                    CoStepHistoryRepository $coStepHisRepo)
    {
        $this->deliRepo                         = $deliRepo;
        $this->coRepo                           = $coRepo;
        $this->coStepHisRepo                    = $coStepHisRepo;
        $this->menu                             = [
            'root' => 'Quản lý Giao nhận',
            'data' => [
                'parent' => [
                    'href'   => route('admin.customer.index'),
                    'label'  => 'Giao nhận'
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
        $coreCustomers              = AdminHelper::getCoreCustomer();

        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        if ($request->input('core_customer_id')) {
            $params['core_customer_id'] = $request->core_customer_id;
        }

        if (in_array($request->input('status_customer_received'), ['0','1'])) {
            $params['status_customer_received'] = $request->status_customer_received;
        }
        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.delivery.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $datas = $this->deliRepo->getDeliverys($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.delivery.index',compact('breadcrumb', 'titleForLayout', 'datas', 'coreCustomers'));
    }

    public function create(Request $request)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $coreCustomers              = AdminHelper::getCoreCustomer();
        $shippingUnit               = AdminHelper::getShippingUnitDelivery();
        $shippingMethod             = AdminHelper::getShippingMethodDelivery();

        $params = $request->all();
        $coId = isset($params['co_id']) ? $params['co_id'] : null;
        $coreCustomerId = isset($params['core_customer_id']) ? $params['core_customer_id'] : null;
        if ($coId) {
            $queryCo    = $this->coRepo->getCoes([
                'id'     => $coId,
                'status' => ProcessStatus::Approved
            ])->limit(1);
            $coModel = $queryCo->first();
            $co = $queryCo->pluck('code', 'id')->toArray();
            if (!$co) {
                return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
            }

            return view('admins.delivery.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'co',
                'coreCustomers', 'coreCustomerId', 'coModel', 'shippingUnit', 'shippingMethod'));
        }

        return redirect()->back()->withInput()->with('error', 'Vui lòng kiểm tra lại CO!');
    }

    public function store(DeliveryRequest $request)
    {
        $input = $request->input();
        $coId = (isset($input['co_id']) && $input['co_id']) ? $input['co_id'] : null;
        try {
            $co = $this->coRepo->find($coId);
            if (!$co) {
                return redirect()->back()->withInput()->with('error','CO không tồn tại!');
            }
            \DB::beginTransaction();

            // Save delivery
            $input['admin_id'] = Session::get('login')->id;
            $input['status_customer_received'] = (int) $request->input('status_customer_received');
            $delivery          = Delivery::create($input);
            $receipt = $delivery->co->receipt()->where('status', 1)->orderBy('step_id', 'desc')->first();
            if($delivery) {
                if($receipt && $receipt->step_id == 2){
                    $this->coStepHisRepo->insertNextStep('receipt', $delivery->co_id, $receipt->id, CoStepHistory::ACTION_APPROVE,4);
                } else {
                    $this->coStepHisRepo->insertNextStep('delivery', $delivery->co_id, $delivery->id, CoStepHistory::ACTION_APPROVE);
                }
            }

            // Save timeline
            if ($input['status_customer_received']) {
                $co->start_timeline = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $co->start_timeline = null;
            }
            $co->delivery_id = $delivery->id;
            $co->save();
            \DB::commit();
            return redirect()->route('admin.delivery.index')->with('success', 'Tạo giao nhận thành công!');
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.delivery.index')->with('error','Tạo giao nhận thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $model                      = $this->deliRepo->find($id);

        if ($model) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.delivery.index-all') && $model->admin_id != $user->id) {
                return redirect()->route('admin.delivery.index')->with('error', 'Bạn không có quyền truy cập!');
            }

            $permissions             = config('permission.permissions');
            $coreCustomers           = AdminHelper::getCoreCustomer();
            $shippingUnit            = AdminHelper::getShippingUnitDelivery();
            $shippingMethod             = AdminHelper::getShippingMethodDelivery();

            $coId = $model->co_id;
            $coreCustomerId = $model->core_customer_id;
            if ($coId) {
                $queryCo    = $this->coRepo->getCoes([
                    'id'     => $coId,
                    'status' => ProcessStatus::Approved
                ])->limit(1);
                $coModel = $queryCo->first();
                $co = $queryCo->pluck('code', 'id')->toArray();
                if (!$co) {
                    return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
                }
            }

            return view('admins.delivery.edit',compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'coreCustomers', 'co', 'coreCustomerId', 'coModel', 'shippingUnit', 'shippingMethod'));
        }
        return redirect()->route('admin.delivery.index')->with('error', 'Giao hàng không tồn tại!');
    }

    public function update(DeliveryRequest $request, $id)
    {
        $model = $this->deliRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            try {
                $co = $this->coRepo->find($model->co_id);
                if (!$co) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                \DB::beginTransaction();

                // Save delivery
                $input['status_customer_received'] = (int) $request->input('status_customer_received');
                $delivery = $this->deliRepo->update($inputs, $id);
                if($input['status_customer_received'] && $co->currentStep && $co->currentStep->step == CoStepHistory::STEP_WAITING_APPROVE_DELIVERY){
                    $isEnoughExportSell = $this->coRepo->checkQuantityExportSell($co);
                    if(!$isEnoughExportSell) {
                        $this->insertNextStep('manufacture', $co->id, $co->id, CoStepHistory::ACTION_APPROVE);
                    } else {
                        $this->coStepHisRepo->insertNextStep('receipt', $co->id, $co->id, CoStepHistory::ACTION_CREATE, 3);
                    }
                }

                // Save timeline
                if ($input['status_customer_received']) {
                    $co->start_timeline = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                } else {
                    $co->start_timeline = null;
                }
                $co->delivery_id = $delivery->id;
                $co->save();
                \DB::commit();
                return redirect()->route('admin.delivery.edit', ['id' => $id])->with('success','Cập nhật giao nhận thành công!');
            } catch(\Exception $ex) {
                \DB::rollback();
                report($ex);
            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật giao nhận không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Giao nhận không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->cusRepo->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.customer.index')->with('success','Xóa khách hàng thành công!');
        }
        return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
    }
}
