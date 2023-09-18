<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Admin;
use App\Models\CoreCustomer;
use App\Models\Repositories\BankHistoryTransactionRepository;
use App\Models\Repositories\BankRepository;
use App\Models\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\ConfigRepository;
use App\Models\Repositories\DeliveryRepository;
use App\Models\Repositories\CoTmpRepository;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    protected $bankRepository;
    protected $bankHistoryTransactionRepository;
    protected $cusRepo;
    protected $coRepo;
    protected $configRepo;
    protected $deliRepo;
    protected $coTmpRepo;

    public $menu;

    function __construct(BankRepository $bankRepository,
                         BankHistoryTransactionRepository $bankHistoryTransactionRepository,
                         CustomerRepository $cusRepo,
                         CoRepository $coRepo,
                         ConfigRepository $configRepo,
                         DeliveryRepository $deliRepo,
                         CoTmpRepository $coTmpRepo)
    {
        $this->bankRepository                   = $bankRepository;
        $this->bankHistoryTransactionRepository = $bankHistoryTransactionRepository;
        $this->cusRepo                          = $cusRepo;
        $this->coRepo                           = $coRepo;
        $this->configRepo                       = $configRepo;
        $this->deliRepo                         = $deliRepo;
        $this->coTmpRepo                         = $coTmpRepo;
        $this->menu                             = [
            'root' => 'Quản lý Khách hàng',
            'data' => [
                'parent' => [
                    'href'   => route('admin.customer.index'),
                    'label'  => 'Khách hàng'
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
        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }
        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.customer.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $datas = $this->cusRepo->search($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.customers.index',compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create()
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        return view('admins.customers.create',compact('breadcrumb', 'titleForLayout', 'permissions'));
    }

    public function store(CustomerRequest $request)
    {
        $input = $request->input();
        $input['admin_id'] = Session::get('login')->id;
        $bank  = CoreCustomer::create($input);
        if ($bank) {
            return redirect()->route('admin.customer.index')->with('success','Tạo khách hàng thành công!');
        }
        return redirect()->route('admin.customer.index')->with('error','Tạo khách hàng thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $coes                       = $this->coRepo->getCoes(['core_customer_id' => $id])->orderBy('id','DESC')->paginate(10);
        $co_tmps                    = $this->coTmpRepo->getCoes(['core_customer_id' => $id])->orderBy('id','DESC')->paginate(10);
        $deliveries                 = $this->deliRepo->getDeliverys(['core_customer_id' => $id])->orderBy('id','DESC')->paginate(10);
        $limitApprovalCg            = $this->configRepo->getConfigs(['key' => 'limit_approval_cg'])->first()->value;
        $model                      = $this->cusRepo->find($id);

        if ($model) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.customer.index-all') && $model->admin_id != $user->id) {
                return redirect()->route('admin.bank.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions             = config('permission.permissions');
            return view('admins.customers.edit',compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'coes', 'limitApprovalCg', 'deliveries', 'co_tmps'));
        }
        return redirect()->route('admin.customer.index')->with('error', 'Khách hàng không tồn tại!');
    }

    public function update(CustomerRequest $request, $id)
    {
        $model = $this->cusRepo->find($id);
        if ($model) {
            $inputs = $request->input();
            $result = $this->cusRepo->update($inputs, $id);
            return redirect()->route('admin.customer.edit', ['id' => $id])->with('success','Cập nhật khách hàng thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Khách hàng không tồn tại!');
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
