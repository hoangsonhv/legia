<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Enums\QCCheckStatus;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\CoStepHistory;
use App\Models\Manufacture;
use App\Models\ManufactureDetail;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\CoTmpRepository;
use App\Models\Repositories\PaymentRepository;
use App\Models\Repositories\ReceiptRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\BankRepository;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\ManufactureRepository;
use Illuminate\Http\Request;
use App\Models\Co;
use App\Models\CoTmp;
use App\Models\Repositories\RequestStepHistoryRepository;
use App\Models\RequestStepHistory;
use Illuminate\Support\Carbon;

class BaseAdminController extends Controller
{
    protected $coRepository;
    protected $requestRepository;
    protected $paymentRepository;
    protected $receiptRepository;
    protected $bankRepo;
    protected $coStepHistoryRepo;
    protected $requestStepHistoryRepository;
    protected $manufactureRepository;
    protected $coTmpRepository;

    function __construct(
        CoTmpRepository $coTmpRepository,
        CoRepository $coRepository,
        RequestRepository $requestRepository,
        PaymentRepository $paymentRepository,
        ReceiptRepository $receiptRepository,
        BankRepository $bankRepo,
        CoStepHistoryRepository $coStepHistoryRepo,
        RequestStepHistoryRepository $requestStepHistoryRepository,
        ManufactureRepository $manufactureRepository
    )
    {
        $this->coTmpRepository = $coTmpRepository;
        $this->coRepository = $coRepository;
        $this->requestRepository = $requestRepository;
        $this->paymentRepository = $paymentRepository;
        $this->receiptRepository = $receiptRepository;
        $this->coStepHistoryRepo = $coStepHistoryRepo;
        $this->requestStepHistoryRepository = $requestStepHistoryRepository;
        $this->manufactureRepository = $manufactureRepository;
        $this->bankRepo = $bankRepo;
    }

    public function approval(Request $request)
    {
       try {
            \DB::beginTransaction();
            $id = $request->input('id');
            $type = $request->input('type');
            $status = $request->input('status');
            $note = $request->has('note') ? $request->input('note') : '';
            $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM]);
            switch ($type) {
                case 'co-tmp':
                    $repository = $this->coTmpRepository->find($id);
                    break;
                case 'co':
                    $repository = $this->coRepository->find($id);
                    if (!$this->coRepository->approvedEligibility($repository) && $status == ProcessStatus::Approved) {
                        return redirect()->back()->with('error', 'Vui lòng kiểm tra lại thông tin thanh toán theo từng giai đoạn!');
                    }
                    break;
                case 'request':
                    $repository = $this->requestRepository->find($id);
                    // Check price_survey
                    $message = '';
                    if(!$this->requestRepository->checkBuyPriceSurvey($repository, $message)) {
                        return redirect()->back()->with('error', $message);
                    }
                    if (!$this->requestRepository->checkQuantityMaterial($repository) && $status == ProcessStatus::PendingSurveyPrice) {
                        return redirect()->back()->with('error', 'Số lượng từng vật liệu phải lớn hơn 0!');
                    }
                    if (!$this->requestRepository->checkConditionApproved($repository) && $status == ProcessStatus::Approved) {
                        return redirect()->back()->with('error', 'Vui lòng điền tổng thanh toán và nội dung thanh toán từng giai đoạn!');
                    }
                    break;
                case 'payment':
                    $repository = $this->paymentRepository->find($id);
                    if (!$this->bankRepo->checkAccountBalance($repository)) {
                        return redirect()->back()->with('error', 'Số tiền trong tài khoản ngân hàng (ATM) không đủ!');
                    }
                    break;
                case 'receipt':
                    $repository = $this->receiptRepository->find($id);
                    break;
                case 'manufacture':
                    $repository = $this->manufactureRepository->find($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'Không tìm thấy thông tin xét duyệt!');
            }

            if ($type == 'manufacture') {
                $qcCheck = $request->input('qc_check');

                if ($qcCheck == \App\Enums\QCCheckStatus::FIX) {
                    $repository->is_completed = 1;
                }

                $repository->qc_check = $qcCheck;
                $repository->save();

                if ($repository->save()) {
                    $stepBack = '';

                    if ($qcCheck == \App\Enums\QCCheckStatus::FIX) {
                        $stepBack = CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE;
                    }
                    else if ($qcCheck == \App\Enums\QCCheckStatus::REMAKE) {
                        $stepBack = CoStepHistory::STEP_CHECKWAREHOUSE;
                        Co::where('id', $repository->co_id)->update(['enough_material' => 0, 'is_remake' => 1]);
                    }

                    if (!empty($stepBack)) {
                        $stepBack = CoStepHistory::where('co_id', $repository->co_id)->where('step', $stepBack)->first();
                        CoStepHistory::where('co_id', $repository->co_id)->where('id', '>', $stepBack->id)->delete();
                    }
                    else {
                        $manufacture = Manufacture::where('co_id', $repository->co_id)->get();
                        $qcCheckDone = 1;

                        if ($manufacture->isNotEmpty()) {
                            foreach ($manufacture as $v) {
                                if ($v->qc_check != \App\Enums\QCCheckStatus::DONE) {
                                    $qcCheckDone = 0;
                                }
                            }
                        }

                        if ($qcCheckDone) {
                            $this->coStepHistoryRepo->insertNextStep('qc-check', $repository->co_id, $repository->co_id, CoStepHistory::ACTION_APPROVE, 2);
                        }
                    }
                }

                \DB::commit();
                return redirect()->back()->with('success', 'Quá trình xét duyệt thành công!');
            }

            if ($repository && array_key_exists($status, ProcessStatus::all())) {
                $repository->status = $status;
                $repository->note = $note;
                if ($type != 'receipt' && ProcessStatus::Unapproved == $status) {
                    $repository->used = 1;
                }

                if($type == 'co' && ProcessStatus::Unapproved) {
                    $coTmp = CoTmp::find($repository->co_tmp_id);
                    if($coTmp) {
                        $coTmp->co_not_approved_id = $repository->id;
                        $coTmp->save();
                    }
                }

                if (($type == 'receipt' && ProcessStatus::Approved)
                    || ($type == 'payment' && ProcessStatus::Approved)) {
                    $repository->approved_date = Carbon::now();
                }

                if ($repository->save()) {
                    switch ($type) {
                        case 'co':
                            if ($status == ProcessStatus::Approved) {
                                // Insert manufacture
                                $this->manufactureRepository->createByCo($repository);
                                $this->coStepHistoryRepo->insertNextStep($type, $repository->id, $repository->id, CoStepHistory::ACTION_APPROVE);
                            }
                            break;
                        case 'payment':
                            if(!$repository->co_id && !in_array($repository->request->category, array_keys($categories))) {
                                $this->requestRepository->doneRequest($repository->request->id);
                                break;
                            }
                            $this->bankRepo->updateAccountBalance($repository);
                            if ($status == ProcessStatus::Approved) {
                                //
                                if($repository->request->co_id) {
                                    switch ($repository->step_id) {
                                        case 0:
                                            $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                            break;
                                        case 1:
                                            $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                            break;
                                        case 2:
                                            $this->coStepHistoryRepo->insertNextStep('warehouse-receipt', $repository->co_id, $repository->request_id, CoStepHistory::ACTION_CREATE);
                                            break;
                                        case 3:
                                            $this->coStepHistoryRepo->insertNextStep('warehouse-export', $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE);
                                            break;
                                    }
                                } else if (in_array($repository->request->category, array_keys($categories))) {
                                    switch ($repository->step_id) {
                                        case 0:
                                            $this->requestStepHistoryRepository->insertNextStep($type, $repository->request_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                            break;
                                        case 1:
                                            $this->requestStepHistoryRepository->insertNextStep($type, $repository->request_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                            break;
                                        case 2:
                                            $this->requestStepHistoryRepository->insertNextStep($type, $repository->request_id, $repository->request_id, CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                            break;
                                        case 3:
                                            $this->requestStepHistoryRepository->insertNextStep('warehouse-receipt', $repository->request_id, $repository->request_id, CoStepHistory::ACTION_CREATE);
                                            break;
                                    }
                                }
                            } else if($status == ProcessStatus::Unapproved) {
                                if($repository->request->co_id) {
                                    $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id);
                                } else if (in_array($repository->request->category, array_keys($categories))) {
                                    $this->requestStepHistoryRepository->insertNextStep($type, $repository->request_id, $repository->request_id,CoStepHistory::ACTION_CREATE, $repository->step_id);
                                }
                            }
                            break;
                        case 'receipt':
                            if(!$repository->co_id) {
                                break;
                            }
                            if ($status == ProcessStatus::Approved) {
                                switch ($repository->step_id) {
                                    case 0:
                                        Manufacture::where('co_id', $repository->co_id)
                                            ->update(['is_completed' => Manufacture::PROCESSING]);
                                        $this->manufactureRepository->checkNeedQuantity($repository->co_id);
                                        $this->coStepHistoryRepo->insertNextStep('check_warehouse', $repository->co_id, $repository->co_id, CoStepHistory::ACTION_SELECT);
                                        break;
                                    case 1:
                                        $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                        break;
                                    case 2:
                                        // dd($repository->co->first()->delivery->id);
//                                        $this->coStepHistoryRepo->insertNextStep('warehouse-export-sell', $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE);
                                        $this->coStepHistoryRepo->insertNextStep('delivery', $repository->co_id, $repository->co->first()->delivery->id, CoStepHistory::ACTION_APPROVE);
                                        break;
                                    case 3:
                                        $this->coRepository->doneCo($repository->co_id);
                                        break;
                                }
                                if ($repository->step_id == 0) {

                                } else {
                                    $this->coStepHistoryRepo->insertNextStep($type, $repository, CoStepHistory::ACTION_CREATE, $repository->step_id + 1);
                                }
                            } else if ($status == ProcessStatus::Unapproved) {
                                switch ($repository->step_id) {
                                    case 0:
                                        $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE, $repository->step_id);
                                        break;
                                    case 1:
                                        $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE, $repository->step_id);
                                        break;
                                    case 2:
                                        $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE, $repository->step_id);
                                        break;
                                    case 3:
                                        $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE, $repository->step_id);
                                        break;
                                }
                            }
                            $this->bankRepo->updateAccountBalance($repository);
                            break;
                        case 'request':
                            if(!$repository->co_id) {
                                if(in_array($repository->category,array_keys(DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM, DataHelper::HOAT_DONG, DataHelper::DINH_KY])))) {
                                    if ($status == ProcessStatus::PendingSurveyPrice) {
                                        $this->requestStepHistoryRepository->insertNextStep($type, $repository->id, $repository->id, RequestStepHistory::ACTION_CREATE_PRICE_SURVEY);
                                    } else if ($status == ProcessStatus::Approved) {
                                        // dd('123123');
                                        if(in_array($repository->category,array_keys(DataHelper::getCategoriesForIndex([DataHelper::HOAT_DONG, DataHelper::DINH_KY])))) {
                                            $this->requestStepHistoryRepository->insertNextStep('payment', $repository->id, $repository->id, RequestStepHistory::ACTION_CREATE, 3);
                                        } else {
                                            $this->requestStepHistoryRepository->insertNextStep('payment', $repository->id, $repository->id, RequestStepHistory::ACTION_CREATE, 0);
                                        }
                                    } else if ($status == ProcessStatus::Unapproved) {
                                        $this->requestStepHistoryRepository->insertNextStep('request', $repository->id, $repository->id, RequestStepHistory::ACTION_CREATE);
                                    }
                                }
                                break;
                            }
                            if ($status == ProcessStatus::PendingSurveyPrice) {
                                $this->coStepHistoryRepo->insertNextStep($type, $repository->co_id, $repository->id, CoStepHistory::ACTION_CREATE_PRICE_SURVEY);
                            } else if ($status == ProcessStatus::Approved) {
                                $this->coStepHistoryRepo->insertNextStep('payment', $repository->co_id, $repository->id, CoStepHistory::ACTION_CREATE, 0);
                            } else if ($status == ProcessStatus::Unapproved) {
                                $this->coStepHistoryRepo->insertNextStep('request', $repository->co_id, $repository->co_id, CoStepHistory::ACTION_CREATE);
                            }
                            break;
                        default:
                            break;
                    }
                    \DB::commit();
                    return redirect()->back()->with('success', 'Quá trình xét duyệt thành công!');
                }
            }
       } catch (\Exception $ex) {
           dd($ex);
       }
        return redirect()->back()->with('error', 'Quá trình xét duyệt thất bại!');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkWarehouse(Request $request)
    {
        try {
            $coId = $request->input('id');
            $value = $request->input('value');
            if (!$coId || !$value) {
                return redirect()->back()->with('error', 'Quá trình xét duyệt thất bại!');
            }
            $co = $this->coRepository->find($coId);
            if (!$co) {
                return redirect()->back()->withInput()->with('error', 'CO không tồn tại!');
            }

            \DB::beginTransaction();
            $co->enough_material = $value;
            $co->save();

            if ($value == Co::ENOUGH_MATERIAL) {
                $this->coStepHistoryRepo->insertNextStep('warehouse-export', $coId, $coId, CoStepHistory::ACTION_CREATE);
            } else if ($value == Co::LACK_MATERIAL) {
                $this->coStepHistoryRepo->insertNextStep('request', $coId, $coId,CoStepHistory::ACTION_CREATE);
            }

            \DB::commit();
            return redirect()->back()->with('success', 'Quá trình xét duyệt thành công!');
        } catch (\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->back()->with('error', 'Quá trình xét duyệt thất bại!');
    }
}
