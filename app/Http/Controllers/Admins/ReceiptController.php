<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptRequest;
use App\Models\Admin;
use App\Models\CoStepHistory;
use App\Models\Receipt;
use App\Models\Repositories\ConfigRepository;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\PaymentRepository;
use App\Models\Repositories\ReceiptRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Services\CoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReceiptController extends Controller
{
    private $disk = 'local';

    protected $paymentRepository;
    protected $receiptRepository;
    protected $coRepository;
    protected $coStepHisRepo;
    protected $configRepository;

    public $menu;

    function __construct(PaymentRepository $paymentRepository,
                         ReceiptRepository $receiptRepository,
                         CoRepository $coRepository,
                         CoStepHistoryRepository $coStepHisRepo,
                         ConfigRepository $configRepository
                         )
    {
        $this->paymentRepository = $paymentRepository;
        $this->configRepository  = $configRepository;
        $this->receiptRepository = $receiptRepository;
        $this->coRepository      = $coRepository;
        $this->coStepHisRepo     = $coStepHisRepo;
        $this->menu              = [
            'root' => 'Quản lý Phiếu Thu',
            'data' => [
                'parent' => [
                    'href'   => route('admin.receipt.index'),
                    'label'  => 'Quản lý Phiếu Thu'
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
        $statuses[0]                = 'TẤT CẢ';
        ksort($statuses);
        $paymentMethods = DataHelper::getPaymentMethods();

        // search
        if($request->has('key_word')) {
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
        }
        $arrParms = $request->all();
        $this->receiptRepository->setParams($params, $arrParms);
        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.receipt.index-all')) {
            $params['admin_id'] = $user->id;
        }
        $receipts       = $this->receiptRepository->getReceipts($params)->orderBy('id','DESC')->paginate($limit);
        $count = [
            $this->receiptRepository->countByStatus(),
            $this->receiptRepository->countByStatus(ProcessStatus::Pending),
            $this->receiptRepository->countByStatus(ProcessStatus::Approved),
            $this->receiptRepository->countByStatus(ProcessStatus::Unapproved)
        ];
        $request->flash();
        return view('admins.receipts.index',compact('breadcrumb', 'titleForLayout', 'statuses', 'paymentMethods', 'receipts','count'));
    }

    public function create(Request $request, $type=null, $id=null)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $files                      = DataHelper::getFiles();
        $paymentMethods             = DataHelper::getPaymentMethods();
        $banks                      = AdminHelper::getBanks();

        $coTmp = null;
        $receipt = null;
        $co = null;
        $thanhToan = null;
        $receipts = [];
        if ($type && $id) {
            switch ($type) {
                case 'co':
                    $repository = $this->coRepository->getCoes([
                        'id'     => $id,
                        'status' => ProcessStatus::Approved
                    ])->first();
                    if($repository) {
                        $co = $repository;
                        $thanhToan = $repository->thanh_toan;
                        $percents = isset($thanhToan['percent']) ? $thanhToan['percent'] : [];
                        $steps = DataHelper::stepPay();
                        $indexStepPay = 0;
                        foreach ($steps as $step) {
                            if(isset($percents[$step['field']]) && $percents[$step['field']]) {
                                $receipt = $this->receiptRepository->getReceipts([
                                    'co_id' => $id,
                                    'step_id' => $indexStepPay
                                ])->orderBy('id', 'DESC')->first();
                                if(!$receipt || !in_array($receipt->status, [ProcessStatus::Pending, ProcessStatus::Approved])) {
                                    break;
                                }
                            } else if ($step['field'] == "thanh_toan_no") {
                                break;
                            }
                            $indexStepPay += 1;
                        }
                        if($indexStepPay == 3) {
                            $payment_documents = [];
                            if (isset($thanhToan['payment_document'])) {
                                $paymentDocuments = $thanhToan['payment_document'];
                            }

                            $flag = true;
                            $keyPaymentDocuments = CoService::paymentDocuments();
                            foreach ($keyPaymentDocuments as $key => $doc) {
                                if(isset($paymentDocuments['required_'.$key])) {
                                    $flag = (isset($paymentDocuments['finished_'.$key]) && $paymentDocuments['required_'.$key] == $paymentDocuments['finished_'.$key]);
                                }
                            }
                            if(!$flag) {
                                return redirect()->route('admin.co.edit', ['id' => $repository->id])->with('error','Chưa đủ chứng từ thanh toán!');
                            }
                        }
                        if(!isset($steps[$indexStepPay])) {
                            return redirect()->back()->with('error','Chỉ được tạo 4 phiếu thu cho 1 CO');
                        }
                        $sumRc = $co->receipt->where('status', 2)->sum('actual_money');
                        $receipt = new Receipt;
                        $receipt->money_total = $steps[$indexStepPay]['field'] !== "thanh_toan_no" ? $thanhToan['amount_money'][$steps[$indexStepPay]['field']] : (($co->tong_gia + $co->vat) - $sumRc);
                        $receipt->note = 'Tạo phiếu thu cho ' . $repository->code . ' ' . $steps[$indexStepPay]['text'];
                        $receipt->step_id = $indexStepPay;
                        $arrReceipts = $repository->receipt()->get()->toArray();
                        foreach ($arrReceipts as $recod) {
                            if(in_array($recod['status'], [ProcessStatus::Pending, ProcessStatus::Approved])) {
                                $receipts[$recod['step_id']] = $recod;
                            }
                        }
                    }
                    if($coTmpData = $repository->co_tmp()->first())
                    {
                        $coTmp = $coTmpData->warehouses;
                    }
                    break;
                case 'payment':
                    $repository = $this->paymentRepository->getPayments([
                        'id'     => $id,
                        'status' => ProcessStatus::Approved
                    ])->first();
                    break;
            }
            if (!empty($repository)) {
                $coes[$repository->id] = $repository->code;
            }
        }
        if (empty($coes)) {
            return redirect()->back()->with('error','Thông tin CO không tồn tại!');
        }
        return view('admins.receipts.create',compact('receipts', 'breadcrumb', 'titleForLayout', 'permissions',
            'type', 'coes', 'thanhToan', 'co', 'files', 'paymentMethods', 'banks', 'receipt', 'coTmp'));
    }

    public function store(ReceiptRequest $request)
    {
//        try {
            if ($request->input('co_id')) {
                $coId = $request->input('co_id');
                $co   = $this->coRepository->find($coId);
                if (!$co || $co->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                $coId      = $co->id;
                $coCode    = $co->code;
                $paymentId = null;
            } else if($request->input('payment_id')) {
                $paymentId = $request->input('payment_id');
                $payment   = $this->paymentRepository->find($paymentId);
                if (!$payment || $payment->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','Phiếu Chi không tồn tại!');
                }
                $co = $this->coRepository->find($payment->co_id);
                if (!$co || $co->status !== ProcessStatus::Approved) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                $coId   = $co->id;
                $coCode = $co->code;
            } else {
                return redirect()->back()->withInput()->with('error','Thông tin CO không tồn tại!');
            }

            // Upload file
            $files     = $request->file('accompanying_document');
            $documents = [];
            if ($files) {
                $path = 'uploads/receipts/accompanying_document';
                foreach($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($documents) {
                            foreach($documents as $document) {
                                Storage::disk($this->disk)->delete($document);
                            }
                        }
                        break;
                    }
                    $documents[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }

            \DB::beginTransaction();
            $actual_money = (int)str_replace(',', '', $request->input('tmp_money_total'));
            $debt_money = $request->input('money_total') - $actual_money;
            $input = [
                'co_id'                 => $coId,
                'co_code'               => $coCode,
                'code'                  => $this->receiptRepository->getIdCurrent(),
                'payment_id'            => $paymentId,
                'payment_method'        => $request->input('payment_method'),
                'note'                  => $request->input('note'),
                'admin_id'              => Session::get('login')->id,
                'name_receiver'         => $request->input('name_receiver'),
                'accompanying_document' => json_encode($documents),
                'money_total'           => $request->input('money_total'),
                'debt_money'            => (string)$debt_money,
                'actual_money'          => (string)$actual_money,
                'bank_id'           => $request->input('bank_id'),
                'step_id'           => $request->input('step_id'),
            ];
            // Save receipt
            $receipt = Receipt::create($input);
            // Save receipt
            // Save co_step_history
            $limitApprovalRc = $this->configRepository->getConfigs(['key' => 'limit_approval_rc'])->first()->value;
            $moneyLimitApprove = $request->input('money_total') * $limitApprovalRc / 100;
            if($request->input('co_id')) {
                //dd($request->input());
                if($debt_money <= $moneyLimitApprove) {
                    if($request->input('step_id') == 1) {
                        //dd('here');
                        $this->coStepHisRepo->insertNextStep('warehouse-export-sell', $request->input('co_id'), $request->input('co_id'), CoStepHistory::ACTION_CREATE);
                    } else if($request->input('step_id') == 4) {
                        $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'), $receipt->id, CoStepHistory::ACTION_APPROVE,5);
                    }
                    else {
                        $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'),$request->input('step_id') == 2 ? $request->input('co_id') :  $receipt->id, CoStepHistory::ACTION_APPROVE, $request->input('step_id') );
                    }
                } else {
                    $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'), $receipt->id, CoStepHistory::ACTION_UPDATE, $request->input('step_id') );
                }
            }
            // \DB::rollBack();
            // dd($request->input('step_id'));
            // Save relationship
            if (!empty($co)) {
                $receipt->co()->save($co);
                $co->used = 1;
                $co->save();
            }
            if (!empty($payment)) {
                $payment->used = 1;
                $payment->save();
            }
            \DB::commit();
            return redirect()->route('admin.receipt.index')->with('success','Tạo Phiếu Thu thành công!');
//        } catch(\Exception $ex) {
//            \DB::rollback();
//            report($ex);
//        }
        return redirect()->route('admin.receipt.index')->with('error','Tạo Phiếu Thu thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $receipt                    = $this->receiptRepository->find($id);
        $banks                      = AdminHelper::getBanks();

        $co = null;
        $thanhToan = null;
        $receipts = [];
        if ($receipt) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.receipt.index-all') && $receipt->admin_id != $user->id) {
                return redirect()->route('admin.receipt.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions    = config('permission.permissions');
            $files          = DataHelper::getFiles();
            $paymentMethods = DataHelper::getPaymentMethods();
            $coTmp = null;
            if ($receipt->payment_id) {
                $repository = $this->paymentRepository->getPayments([
                        'id'     => $receipt->payment_id,
                        'status' => ProcessStatus::Approved
                    ])->first();
                if ($repository) {
                    $coes[$repository->id] = $repository->code;
                    $type                = 'payment';
                }
            } else if ($receipt->co_id) {
                $repository = $this->coRepository->getcoes([
                        'id'     => $receipt->co_id,
                        'status' => ProcessStatus::Approved
                    ])->first();
                if ($repository) {
                    $co = $repository;
                    $coes[$repository->id] = $repository->code;
                    $type                = 'co';
                    $thanhToan = $repository->thanh_toan;

                    $arrReceipts = $repository->receipt()->get()->toArray();
                    foreach ($arrReceipts as $recod) {
                        if(in_array($recod['status'], [ProcessStatus::Pending, ProcessStatus::Approved])) {
                            $receipts[$recod['step_id']] = $recod;
                        }
                    }
                }
                if($coTmpData = $repository->co_tmp()->first())
                {
                    $coTmp = $coTmpData->warehouses;
                }
            }
            if (empty($coes)) {
                return redirect()->back()->with('error','Thông tin CO không tồn tại!');
            }
            return view('admins.receipts.edit',compact('breadcrumb', 'titleForLayout', 'receipt',
                'permissions', 'type', 'receipts', 'co', 'coes', 'thanhToan', 'files', 'paymentMethods', 'banks', 'coTmp'));
        }
        return redirect()->route('admin.receipt.index')->with('error', 'Phiếu Thu không tồn tại!');
    }

    public function update(ReceiptRequest $request, $id)
    {
        try {
            $inputCoId = $request->input('co_id');
            $paymentId = $request->input('payment_id');
            if ($paymentId) {
                $payment = $this->paymentRepository->find($paymentId);
                if (!$payment) {
                    return redirect()->back()->withInput()->with('error','Phiếu Chi không tồn tại!');
                }
                $co = $payment->co->first();
                if (!$co) {
                    return redirect()->back()->withInput()->with('error','Phiếu Chi không tồn tại CO! (Ví dụ: Phiếu Chi Định Kỳ)');
                }
                $coId   = $payment->co_id;
                $coCode = $payment->co_code;
            } elseif ($inputCoId) {
                $co = $this->coRepository->find($inputCoId);
                if (!$co) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                $coId   = $co->id;
                $coCode = $co->code;
            } else {
                return redirect()->back()->withInput()->with('error','Thông tin CO không tồn tại!');
            }

            $receipt = $this->receiptRepository->find($id);
            if ($receipt) {
                // Upload file
                $files     = $request->file('accompanying_document');
                $documents = [];
                if ($files) {
                    $path = 'uploads/receipts/accompanying_document';
                    foreach($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($documents) {
                                foreach($documents as $document) {
                                    Storage::disk($this->disk)->delete($document);
                                }
                            }
                            break;
                        }
                        $documents[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                $documents = array_merge(json_decode($receipt->accompanying_document, true), $documents);
                $actual_money = (int)str_replace(',', '', $request->input('tmp_money_total'));
                $debt_money = $request->input('money_total') - $actual_money;

                \DB::beginTransaction();
                // Save receipt
                $receipt->co_id                 = $coId;
                $receipt->co_code               = $coCode;
                $receipt->payment_id            = $paymentId;
                $receipt->payment_method        = $request->input('payment_method');
                $receipt->note                  = $request->input('note');
                $receipt->name_receiver         = $request->input('name_receiver');
                $receipt->accompanying_document = json_encode($documents);
                $receipt->money_total           = $request->input('money_total');
                $receipt->actual_money          = $actual_money;
                $receipt->debt_money            = $request->input('money_total') - $actual_money;
                $receipt->bank_id               = $request->input('bank_id');
                $receipt->save();
                // Save relationship
                /*$receipt->co()->detach();
                if (!empty($co)) {
                    $receipt->co()->sync($co);
                }*/
                \DB::commit();
                $limitApprovalRc = $this->configRepository->getConfigs(['key' => 'limit_approval_rc'])->first()->value;
                $moneyLimitApprove = $request->input('money_total') * $limitApprovalRc / 100;
                if($request->input('co_id')) {
                    //dd($request->input());
                    if($debt_money <= $moneyLimitApprove && (str_contains($co->currentStep->step, 'create') || str_contains($co->currentStep->step, 'update'))) {
                        if($request->input('step_id') == 1) {
                            //dd('here');
                            $this->coStepHisRepo->insertNextStep('warehouse-export-sell', $request->input('co_id'), $request->input('co_id'), CoStepHistory::ACTION_CREATE);
                        } else if($receipt->step_id == 4) {
                            $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'), $receipt->id, CoStepHistory::ACTION_APPROVE,5);
                        } else {
                            $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'),$request->input('step_id') == 2 ? $request->input('co_id') :  $receipt->id, CoStepHistory::ACTION_APPROVE, $request->input('step_id'));
                        }
                    } else if($debt_money > $moneyLimitApprove && !str_contains($co->currentStep->step, 'update')) {
                        if($receipt->step_id == 4) {
                            $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'), $receipt->id, CoStepHistory::ACTION_UPDATE,5);
                        } 
                        $this->coStepHisRepo->insertNextStep( 'receipt', $request->input('co_id'), $receipt->id, CoStepHistory::ACTION_UPDATE, $request->input('step_id') );
                    }
                }
                return redirect()->route('admin.receipt.edit', ['id' => $id])->with('success','Cập nhật Phiếu Thu thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            dd($ex);
            report($ex);
        }
        return redirect()->back()->with('error', 'Phiếu Thu không tồn tại!');
    }

    public function destroy($id)
    {
        try {
            $receipt = $this->receiptRepository->find($id);
            if ($receipt) {
                $files = json_decode($receipt->accompanying_document, true);
                \DB::beginTransaction();
                $receipt->co()->detach();
                $receipt->delete();
                \DB::commit();
                if ($files) {
                    foreach($files as $file) {
                        Storage::disk($this->disk)->delete($file);
                    }
                }
                return redirect()->route('admin.receipt.index')->with('success','Xóa Phiếu Thu thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->with('error', 'Phiếu Thu không tồn tại!');
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

    public function getPayments(Request $request) {
        $params = [
            ['co_id', 'notNull', '']
        ];
        $keyword = $request->input('keyword_co');
        if (!$keyword) {
            $payments = $this->paymentRepository->getPayments($params)->orderBy('id','DESC')->limit('10');
            $data    = $payments->pluck('code', 'id')->toArray();
            $data[0] = 'Không có';
            return $data;
        } else {
            $params['code'] = ['code', 'like', $keyword];
            $payments       = $this->paymentRepository->getPayments($params)->orderBy('id','DESC')->limit('10');
            return $payments->pluck('code', 'id')->toArray();
        }
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->receiptRepository->find($id);
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
}
