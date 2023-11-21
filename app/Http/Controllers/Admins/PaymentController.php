<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\CoStepHistory;
use App\Models\Payment;
use App\Models\Repositories\PaymentRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Services\CoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $disk = 'local';

    protected $paymentRepository;
    protected $requestRepository;
    protected $coStepHisRepo;

    public $menu;

    function __construct(PaymentRepository $paymentRepository,
                         RequestRepository $requestRepository,
                         CoStepHistoryRepository $coStepHisRepo)
    {
        $this->paymentRepository = $paymentRepository;
        $this->requestRepository = $requestRepository;
        $this->coStepHisRepo     = $coStepHisRepo;
        $this->menu              = [
            'root' => 'Quản lý Phiếu Chi',
            'data' => [
                'parent' => [
                    'href'   => route('admin.payment.index'),
                    'label'  => 'Quản lý Phiếu Chi'
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
        ksort($statuses);

        // Count data pending
        $countPending = $this->paymentRepository->getPayments([
            'used' => 0,
            'co_id' => ['co_id', 'notNull', '']
        ])->count();

        // search
        if($request->has('used')) {
            $params['used'] = $request->used;
        } else if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
            if ($request->input('status')) {
                $params['status'] = $request->status;
            }
        }

        $user = Session::get('login');
        if(!PermissionHelper::hasPermission('admin.payment.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $payments   = $this->paymentRepository->getPayments($params)->orderBy('id','DESC')->paginate($limit);
        $categories = DataHelper::getCategories();
        $request->flash();
        return view('admins.payments.index',compact('breadcrumb', 'titleForLayout', 'statuses', 'countPending', 'payments', 'categories'));
    }

    public function create(Request $request, $requestId=null)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');
        $files                      = DataHelper::getFiles();
        $categories                 = DataHelper::getCategories();
        $paymentMethods             = DataHelper::getPaymentMethods();
        $banks                      = AdminHelper::getBanks();
        $co = null;
        $payment = null;
        $payments = [];
        $thanhToan = null;

        if ($requestId) {
            $requestModel = $this->requestRepository->getRequests([
                    'id'     => $requestId,
                    'status' => ProcessStatus::Approved
                ])
                ->first();
            if ($requestModel) {
                $requests[$requestModel->id] = $requestModel->code;
                $thanhToan = $requestModel->thanh_toan;
                $percents = isset($thanhToan['percent']) ? $thanhToan['percent'] : [];
                $steps = DataHelper::stepPay();
                $indexStepPay = 0;
                foreach ($steps as $step) {
                    if(isset($percents[$step['field']]) && $percents[$step['field']]) {
                        $receipt = $this->paymentRepository->getPayments([
                            'request_id' => $requestId,
                            'step_id' => $indexStepPay
                        ])->first();
                        if(!$receipt || !in_array($receipt->status, [ProcessStatus::Pending, ProcessStatus::Approved])) {
                            break;
                        }
                    }
                    $indexStepPay += 1;
                }
                if($indexStepPay == 3) {
                    $paymentDocuments = isset($thanhToan['payment_document']) ? $thanhToan['payment_document'] : [];
                    $flag = true;
                    $keyPaymentDocuments = CoService::paymentDocuments();
                    foreach ($keyPaymentDocuments as $key => $doc) {
                        if(isset($paymentDocuments['required_'.$key])) {
                            $flag = (isset($paymentDocuments['finished_'.$key]) && $paymentDocuments['required_'.$key] == $paymentDocuments['finished_'.$key]);
                        }
                    }
                    if(!$flag) {
                        return redirect()->route('admin.request.edit', ['id' => $requestModel->id])->with('error','Chưa đủ chứng từ thanh toán!');
                    }
                }
                if(!isset($steps[$indexStepPay])) {
                    return redirect()->back()->with('error','Chỉ được tạo 4 phiếu chi cho 1 phiếu yêu cầu');
                }
                $thanhToan = $requestModel->thanh_toan;

                $arrPayments = $requestModel->payments()->get()->toArray();
                foreach ($arrPayments as $recod) {
                    $payments[$recod['step_id']] = $recod;
                }

                $payment = new Payment;
                $payment->money_total = $thanhToan['amount_money'][$steps[$indexStepPay]['field']];
                $payment->note = 'Tạo phiếu chi cho ' . $requestModel->code . ' ' . $steps[$indexStepPay]['text'];
                $payment->step_id = $indexStepPay;
            }
        }
        if($coData = $requestModel->co()->first())
        {
            $co = $coData->warehouses;
        }
        if (empty($requests)) {
            return redirect()->back()->with('error','Phiếu Yêu Cầu không tồn tại!');
        }
        return view('admins.payments.create',compact('breadcrumb', 'titleForLayout', 'permissions', 'files',
            'categories', 'thanhToan', 'requests', 'payments', 'requestModel', 'paymentMethods', 'banks', 'payment', 'co'));
    }

    public function store(PaymentRequest $request)
    {
        try {
            $requestId    = $request->input('request_id');
            $requestModel = $this->requestRepository->find($requestId);
            if (!$requestModel || $requestModel->status !== ProcessStatus::Approved) {
                return redirect()->back()->withInput()->with('error','Phiếu Yêu Cầu không tồn tại!');
            }
            $coId     = $requestModel->co_id;
            $coCode   = $requestModel->co_code;
            $category = $requestModel->category;

            // Upload file
            $files     = $request->file('accompanying_document');
            $documents = [];
            if ($files) {
                $path = 'uploads/payments/accompanying_document';
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

            \DB::beginTransaction();
            $input = [
                'co_id'                 => $coId,
                'co_code'               => $coCode,
                'category'              => $category,
                'request_id'            => $requestId,
                'code'                  => $this->paymentRepository->getIdCurrent(),
                'note'                  => $request->input('note'),
                'admin_id'              => Session::get('login')->id,
                'name_receiver'         => $request->input('name_receiver'),
                'accompanying_document' => json_encode($documents),
                'money_total'           => $request->input('money_total'),
                'payment_method'        => $request->input('payment_method'),
                'bank_id'               => $request->input('bank_id'),
                'step_id'               => $request->input('step_id'),
            ];
            // Save payment
            $payment = Payment::create($input);
            if($payment) {
                $this->coStepHisRepo->insertNextStep('payment', $payment->co_id, $payment->id, CoStepHistory::ACTION_APPROVE, $request->input('step_id'));
            }
            // Save relationship
            if ($requestModel->co_id) {
                $payment->co()->save($requestModel->co->first());
                $requestModel->used = 1;
                $requestModel->save();
            }
            \DB::commit();
            return redirect()->route('admin.payment.index')->with('success','Tạo Phiếu Chi thành công!');
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.payment.index')->with('error','Tạo Phiếu Chi thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $payment                    = $this->paymentRepository->find($id);
        $paymentMethods             = DataHelper::getPaymentMethods();
        $banks                      = AdminHelper::getBanks();
        if ($payment) {
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.payment.index-all') && $payment->admin_id != $user->id) {
                return redirect()->route('admin.payment.index')->with('error', 'Bạn không có quyền truy cập!');
            }

            $permissions = config('permission.permissions');
            $files       = DataHelper::getFiles();
            $categories  = DataHelper::getCategories();
            $existsCat   = 0;
            $co = null;
            if ($payment->request_id) {
                $requestModel = $this->requestRepository->getRequests([
                        'id'     => $payment->request_id,
                        'status' => ProcessStatus::Approved
                    ])
                    ->first();
                if ($requestModel) {
                    $requests[$requestModel->id] = $requestModel->code;
                }
            }
            if (empty($requests)) {
                return redirect()->back()->with('error','Phiếu Yêu Cầu không tồn tại!');
            }
            if (!$payment->co_id) {
                if (array_key_exists($payment->category, DataHelper::getCategoryPayment(DataHelper::DINH_KY)['option'])) {
                    $start      = Carbon::now()->startOfMonth()->toDatetimeString();
                    $end        = Carbon::now()->endOfMonth()->toDatetimeString();
                    $existsCat  = $this->paymentRepository->getPayments([
                        'id'         => ['id', '!=', $payment->id],
                        'category'   => $payment->category,
                        'status'     => ProcessStatus::Approved,
                        'created_at' => ['created_at', 'between', [$start, $end]]
                    ])->count();
                }
            }
            if($coData = $payment->co()->first())
            {
                $co = $coData->warehouses;
            }
            return view('admins.payments.edit',compact('breadcrumb', 'titleForLayout', 'payment',
                'permissions', 'files', 'categories', 'requests', 'requestModel', 'existsCat', 'paymentMethods',
                'banks','co'));
        }
        return redirect()->route('admin.payment.index')->with('error', 'Phiếu Chi không tồn tại!');
    }

    public function update(PaymentRequest $request, $id)
    {
        try {
            $requestId    = $request->input('request_id');
            $requestModel = $this->requestRepository->find($requestId);
            if (!$requestModel || $requestModel->status !== ProcessStatus::Approved) {
                return redirect()->back()->withInput()->with('error','Phiếu Yêu Cầu không tồn tại!');
            }
            $coId     = $requestModel->co_id;
            $coCode   = $requestModel->co_code;
            $category = $requestModel->category;

            $payment = $this->paymentRepository->find($id);
            if ($payment) {
                $files     = $request->file('accompanying_document');
                $documents = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/payments/accompanying_document';
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
                $documents = array_merge(json_decode($payment->accompanying_document, true), $documents);

                \DB::beginTransaction();
                // Save payment
                $payment->co_id                 = $coId;
                $payment->co_code               = $coCode;
                $payment->category              = $category;
                $payment->request_id            = $requestId;
                $payment->note                  = $request->input('note');
                $payment->name_receiver         = $request->input('name_receiver');
                $payment->accompanying_document = json_encode($documents);
                $payment->money_total           = $request->input('money_total');
                $payment->payment_method        = $request->input('payment_method');
                $payment->bank_id               = $request->input('bank_id');
                $payment->save();
                // Save relationship
                /*$payment->co()->detach();
                if ($requestModel->co_id) {
                    $payment->co()->sync($requestModel->co->first());
                }*/
                \DB::commit();
                return redirect()->route('admin.payment.edit', ['id' => $id])->with('success','Cập nhật Phiếu Chi thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->withInput()->with('error', 'Cập nhật Phiếu Chi thất bại!');
    }

    public function destroy($id)
    {
        try {
            $payment = $this->paymentRepository->find($id);
            if ($payment) {
                $files = json_decode($payment->accompanying_document, true);
                \DB::beginTransaction();
                $payment->co()->detach();
                $payment->delete();
                \DB::commit();
                if ($files) {
                    foreach($files as $file) {
                        Storage::disk($this->disk)->delete($file);
                    }
                }
                return redirect()->route('admin.payment.index')->with('success','Xóa Phiếu Chi thành công!');
            }
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->with('error', 'Phiếu Chi không tồn tại!');
    }

    public function getRequests(Request $request) {
        $keyword = $request->input('keyword_co');
        if (!$keyword) {
            $payment = $this->requestRepository->getRequests()->orderBy('id','DESC')->limit('10');
        } else {
            $payment = $this->requestRepository->getRequests([
                'code' => ['code', 'like', $keyword]
            ])->orderBy('id','DESC')->limit('10');
        }
        return $payment->pluck('code', 'id')->toArray();
    }

    public function removeFile(Request $request) {
        try {
            $id   = $request->input('id');
            $data = $this->paymentRepository->find($id);
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
