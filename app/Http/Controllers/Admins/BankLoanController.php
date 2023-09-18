<?php

namespace App\Http\Controllers\Admins;

use App\Enums\TransactionType;
use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankHistoryTransactionRequest;
use App\Http\Requests\BankLoanRequest;
use App\Http\Requests\BankRequest;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\BankHistoryTransaction;
use App\Models\BankLoanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\BankLoanRepository;

class BankLoanController extends Controller
{
    protected $bankLoanRepository;

    public $menu;

    function __construct(BankLoanRepository $bankLoanRepository)
    {
        $this->bankLoanRepository = $bankLoanRepository;
        $this->menu = [
            'root' => 'Quản lý vay nợ',
            'data' => [
                'parent' => [
                    'href' => route('admin.bank-loans.index'),
                    'label' => 'Quản lý vay nợ'
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
        if ($user['position_id'] == Admin::POSITION_STAFF) {
            $params['admin_id'] = $user->id;
        }
        $datas = $this->bankLoanRepository->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.bank_loans.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create()
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $banks = AdminHelper::getBanks(true);
        return view('admins.bank_loans.create', compact('breadcrumb', 'titleForLayout', 'permissions', 'banks'));
    }

    public function store(BankLoanRequest $request)
    {
        try {
            $inputs = array_map('trim', $request->input());
            $user = Session::get('login');
            $inputs['admin_id'] = $user->id;
            $inputs['code'] = $this->bankLoanRepository->generateCode();
            $inputs['outstanding_balance'] = $inputs['amount_money'] + ($inputs['amount_money'] * $inputs['profit_amount'] / 100);
            $bankLoan = $this->bankLoanRepository->insert($inputs);
            if ($bankLoan) {
                return redirect()->route('admin.bank-loans.index')->with('success', 'Tạo vay nợ thành công!');
            }
            return redirect()->route('admin.bank-loans.index')->with('error', 'Tạo vay nợ thất bại!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.bank-loans.index')->with('error', 'Tạo vay nợ thất bại!');
        }
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        $model = $this->bankLoanRepository->find($id);
        if ($model) {
            $user = Session::get('login');
            if ($user['position_id'] == Admin::POSITION_STAFF && $model->admin_id != $user->id) {
                return redirect()->route('admin.bank.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $modelBanks = Bank::where('id', $model->bank_id)->first();
            $banks = [];
            if($modelBanks) {
                $banks[$modelBanks->id] = $modelBanks->name_bank;
            }

            $limit = 10;
            $queryBankLoanDetails = BankLoanDetail::where('bank_loan_id', $id);
            if(!empty($request->input('from_date'))) {
                $queryBankLoanDetails = $queryBankLoanDetails->where('created_at', '>=', $request->from_date . ' 00:00:00');
            }
            if(!empty($request->input('to_date'))) {
                $queryBankLoanDetails = $queryBankLoanDetails->where('created_at', '<=', $request->to_date . ' 23:59:59');
            }
            $bankLoanDetails = $queryBankLoanDetails->orderBy('id', 'DESC')->paginate($limit);
            return view('admins.bank_loans.edit', compact('breadcrumb', 'titleForLayout', 'model', 'permissions',
                'banks', 'bankLoanDetails'));
        }
        return redirect()->route('admin.bank-loans.index')->with('error', 'Vay nợ không tồn tại!');
    }

    public function update(BankLoanRequest $request, $id)
    {
        $model = $this->bankLoanRepository->find($id);
        if ($model) {
            $inputs = array_map('trim', $request->input());
            $model = $this->bankLoanRepository->update($inputs, $id);
            return redirect()->route('admin.bank-loans.edit', ['id' => $id])->with('success',
                'Cập nhật vay nợ thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Vay nợ không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->bankLoanRepository->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.bank-loans.index')->with('success', 'Xóa vay nợ thành công!');
        }
        return redirect()->back()->with('error', 'Vay nợ không tồn tại!');
    }

    public function insertDetail($id, Request $request)
    {
        $model = $this->bankLoanRepository->find($id);
        if($model) {
            if($model->outstanding_balance < $request->total_amount) {
                return redirect()->back()->with('error', 'Số dư vay nợ nhỏ hơn!. Vui lòng kiểm tra lại');
            }
            $inputs = $request->input();
            $user = Session::get('login');
            $inputs['admin_id'] = $user->id;
            $bankLoanDetail = new BankLoanDetail;
            $bankLoanDetail->fill($inputs);
            $bankLoanDetail->save();
            if($bankLoanDetail) {
                $model->outstanding_balance = $model->outstanding_balance - $request->total_amount;
                $model->save();
                return redirect()->route('admin.bank-loans.edit', ['id' => $id])->with('success','Lưu thông tin thanh toán thành công!');
            }
            return redirect()->back()->with('error', 'Thêm thanh toán không thành công!');
        }
        return redirect()->back()->with('error', 'Vay nợ không tồn tại!');
    }
}
