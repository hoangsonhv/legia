<?php

namespace App\Http\Controllers\Admins;

use App\Enums\TransactionType;
use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use App\Helpers\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankHistoryTransactionRequest;
use App\Http\Requests\BankRequest;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\BankHistoryTransaction;
use App\Models\Repositories\BankHistoryTransactionRepository;
use App\Models\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BankController extends Controller
{
    protected $bankRepository;
    protected $bankHistoryTransactionRepository;

    public $menu;

    function __construct(BankRepository $bankRepository, BankHistoryTransactionRepository $bankHistoryTransactionRepository)
    {
        $this->bankRepository                   = $bankRepository;
        $this->bankHistoryTransactionRepository = $bankHistoryTransactionRepository;
        $this->menu                             = [
            'root' => 'Quản lý tài chính',
            'data' => [
                'parent' => [
                    'href'   => route('admin.bank.index'),
                    'label'  => 'Quản lý tài chính'
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
        if(!PermissionHelper::hasPermission('admin.bank.index-all')) {
            $params['admin_id'] = $user->id;
        }
        $banks = $this->bankRepository->getBanks($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.banks.index',compact('breadcrumb', 'titleForLayout', 'banks'));
    }

    public function create()
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $types                      = AdminHelper::getTypeBanks();
        return view('admins.banks.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'types'));
    }

    public function store(BankRequest $request)
    {
        $input = [
            'name_bank'      => $request->input('name_bank'),
            'account_name'   => $request->input('account_name'),
            'account_number' => $request->input('account_number'),
            'account_balance' => $request->input('account_balance'),
            'opening_balance' => $request->input('opening_balance'),
            'admin_id' => Session::get('login')->id,
        ];
        $bank  = Bank::create($input);
        if ($bank) {
            return redirect()->route('admin.bank.index')->with('success','Tạo tài khoản ngân hàng thành công!');
        }
        return redirect()->route('admin.bank.index')->with('error','Tạo tài khoản ngân hàng thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $bank                       = $this->bankRepository->find($id);
        $typeBanks                  = AdminHelper::getTypeBanks();
        if ($bank) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.bank.index-all') && $bank->admin_id != $user->id) {
                return redirect()->route('admin.bank.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions             = config('permission.permissions');
            $types                   = TransactionType::all();
            $params                  = ['bank_id' => $id];
            $limit                   = 10;

            if(!empty($request->input('type')) && $request->input('type') != 'all') {
                $params['type']  = $request->type;
            }
            if(!empty($request->input('from_date'))) {
                $params['from_date']  = ['created_at', '>=', $request->from_date . ' 00:00:00'];
            }
            if(!empty($request->input('to_date'))) {
                $params['to_date']  = ['created_at', '<=', $request->to_date . ' 23:59:59'];
            }

            $bankHistoryTransactions = $this->bankHistoryTransactionRepository->getBankHistoryTransactions($params)->orderBy('id','DESC')->paginate($limit);
             $request->flash();
            return view('admins.banks.edit',compact('breadcrumb', 'titleForLayout', 'bank', 'permissions',
                'types', 'bankHistoryTransactions', 'typeBanks'));
        }
        return redirect()->route('admin.bank.index')->with('error', 'Tài khoản ngân hàng không tồn tại!');
    }

    public function update(BankRequest $request, $id)
    {
        $bank = $this->bankRepository->find($id);
        if ($bank) {
            $bank->name_bank      = $request->input('name_bank');
            $bank->account_name   = $request->input('account_name');
            $bank->account_number = $request->input('account_number');
            $bank->save();
            return redirect()->route('admin.bank.edit', ['id' => $id])->with('success','Cập nhật tài khoản ngân hàng thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Tài khoản ngân hàng không tồn tại!');
    }

    public function destroy($id)
    {
        $bank = $this->bankRepository->find($id);
        if ($bank) {
            $bank->delete();
            return redirect()->route('admin.bank.index')->with('success','Xóa tài khoản ngân hàng thành công!');
        }
        return redirect()->back()->with('error', 'Tài khoản ngân hàng không tồn tại!');
    }

    public function transaction(BankHistoryTransactionRequest $request, $id){

        $bank = $this->bankRepository->find($id);
        if ($bank) {
//            try {
                // Lấy số tiền từ tài khoản
                $current_amount = $bank->account_balance;
                $currentOpeningBalance = $bank->opening_balance;

                // Check số tiền
                $transaction_amount = preg_replace("/[^0-9.]/", '', $request->input('transaction_amount'));
                if ($request->input('type') === TransactionType::Withdraw && $current_amount < $transaction_amount) {
                    return redirect()->back()->with('error', 'Số tiền không đủ để thực hiện giao dịch Rút!');
                }

                // Cộng trừ tài khoản
                if ($request->input('type') === TransactionType::Withdraw) {
                    $current_amount -= $transaction_amount;
                    $currentOpeningBalance -= $transaction_amount;
                    $bank->account_balance -= $transaction_amount;
                    $bank->opening_balance -= $transaction_amount;
                } else {
                    $current_amount += $transaction_amount;
                    $currentOpeningBalance += $transaction_amount;
                    $bank->account_balance += $transaction_amount;
                    $bank->opening_balance += $transaction_amount;
                }
                $bank->save();

                // Lưu log
                $input = [
                    'bank_id'            => $id,
                    'admin_id'           => Session::get('login')->id,
                    'type'               => $request->input('type'),
                    'transaction_amount' => $transaction_amount,
                    'current_amount'     => $current_amount,
                    'current_opening_balance' => $currentOpeningBalance,
                    'note'               => $request->input('note'),
                ];
                BankHistoryTransaction::create($input);

                \DB::commit();
                return redirect()->route('admin.bank.edit', ['id' => $id])->with('success','Lưu thông tin giao dịch thành công!');
//            } catch(\Exception $ex) {
//                \DB::rollback();
//                report($ex);
//            }
        }
        return redirect()->back()->with('error', 'Tài khoản ngân hàng không tồn tại!');
    }
}
