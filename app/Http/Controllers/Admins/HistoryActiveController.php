<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ChangeHistoryValue;
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
use App\Models\ChangeHistory;
use App\Models\Repositories\BankHistoryTransactionRepository;
use App\Models\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HistoryActiveController extends Controller
{

    public $menu;

    function __construct()
    {
        $this->menu               = [
			'root' => 'Lịch sử hoạt động',
			'data' => [
				'parent' => [
					'href'   => route('admin.history-active.index'),
					'label'  => 'Lịch sử hoạt động'
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
        $query                      = ChangeHistory::query();
        $listFormType               = ChangeHistoryValue::formTypes();
        $listAdmin                  = Admin::get()->pluck('name', 'id');
        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }
        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.bank.index-all')) {
            $params['admin_id'] = $user->id;
        }
        if($request->has('performed_by')) {
            $query->where('performed_by', $request->performed_by);
        }
        if($request->has('formable_type')) {
            $query->where('performed_by', $request->formable_type);
        }
        if($request->has('formable_id')) {
            $query->where('formable_id', $request->formable_id);
        }
        // Lọc theo trạng thái trước đó
        if ($request->has('previous_status')) {
            $query->where('previous_status', $request->previous_status);
        }

        // Lọc theo trạng thái mới
        if ($request->has('new_status')) {
            $query->where('new_status', $request->new_status);
        }

        // Lọc theo khoảng thời gian thực hiện (performed_at)
        if ($request->has('start_date') && $request->has('end_date')) {
            // Lọc theo khoảng từ start_date đến end_date
            $query->whereBetween('performed_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->has('start_date')) {
            // Lọc từ start_date trở đi
            $query->where('performed_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->has('end_date')) {
            // Lọc cho đến end_date
            $query->where('performed_at', '<=', $request->end_date . ' 23:59:59');
        }
        $activities = $query->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.history_active.index',compact('breadcrumb', 'titleForLayout', 'activities','listFormType' ,'listAdmin'));
    }
}
