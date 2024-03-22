<?php

namespace App\Models\Repositories;

use App\Models\CoStepHistory;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\ManufactureRepository;
use App\Models\Manufacture;

class CoStepHistoryRepository extends AdminRepository
{
    protected $coRepo;
    protected $requestRepo;

    public function __construct(CoStepHistory $coStepHistory,
                                CoRepository $coRepo,
                                RequestRepository $requestRepo)
    {
        $this->model = $coStepHistory;
        $this->coRepo = $coRepo;
        $this->requestRepo = $requestRepo;
    }

    public function insertNextStep($type, $coId, $objectId, $action, $stepId = 0)
    {
        $params['co_id'] = $coId;
        $params['object_type'] = $type;
        $params['object_id'] = $objectId;
        $step = null;
        switch ($type) {
            case 'co':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_WAITING_APPROVE_CO;
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    // Nếu phiếu thu lần 1 == 0 thì chuyển kiểm kho.
                    $hasPercent = $this->coRepo->checkPercentPayment($coId, 0);
                    if($hasPercent) {
                        $step = CoStepHistory::STEP_CREATE_RECEIPT_N1;
                    } else {
                        // Change status waiting manufacture
                        Manufacture::where('co_id', $coId)->update(['is_completed' => Manufacture::PROCESSING]);
                        $manufactures = Manufacture::where('co_id', $coId)
                            ->with('details')->get();
                        if($manufactures) {
                            foreach ($manufactures as $manufacture){
                                if(!$manufacture->details->count()) {
                                    $manufacture->is_completed = Manufacture::IS_COMPLETED;
                                    $manufacture->save();
                                }
                            }
                        }
                        $this->insertNextStep('check_warehouse', $coId, $coId, CoStepHistory::ACTION_SELECT);
                    }
                }
                break;
            case 'receipt':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    switch ($stepId) {
                        case 0:
                            $step = CoStepHistory::STEP_CREATE_RECEIPT_N1;
                            break;
                        case 1:
                            // Nếu phiếu thu lần 2 == 0 thì chuyển phiếu xuất kho bán hàng
                            $hasPercent = $this->coRepo->checkPercentPayment($coId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_RECEIPT_N2;
                            } else {
                                $this->insertNextStep('warehouse-export-sell', $coId, $coId, CoStepHistory::ACTION_CREATE);
                            }
                            break;
                        case 2:
                            // Nếu phiếu thu lần 3 == 0 thì chuyển xuất kho bán hàng
                            $hasPercent = $this->coRepo->checkPercentPayment($coId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_RECEIPT_N3;
                            } else {
                                $this->insertNextStep('receipt', $coId, $objectId, CoStepHistory::ACTION_CREATE, 3);
                            }
                            break;
                        case 3:
                            // Nếu phiếu thu lần 4 == 0 thì done CO.
                            $hasPercent = $this->coRepo->checkPercentPayment($coId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_RECEIPT_N4;
                            } else {
                                $co = $this->coRepo->find($coId);
                                $sumRc = $co->receipt->where('status', 2)->sum('actual_money');
                                if($sumRc < ($co->tong_gia + $co->vat))
                                {
                                    $this->insertNextStep('receipt', $coId, $objectId, CoStepHistory::ACTION_CREATE, 4);
                                } else {
                                    $this->coRepo->doneCo($coId);
                                }
                            }
                            break;
                        case 4:
                            $step = CoStepHistory::STEP_CREATE_RECEIPT_EXTRA;

                    }
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    switch ($stepId) {
                        case 0:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N1;
                            break;
                        case 1:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N2;
                            break;
                        case 2:
                            $step = CoStepHistory::STEP_CREATE_DELIVERY;
                            break;
                        case 3:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N4;
                            break;
                        case 4: 
                            $step = CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N3;
                        case 5:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_EXTRA;
                    }
                } else if ($action == CoStepHistory::ACTION_UPDATE) {
                    switch ($stepId) {
                        case 0:
                            $step = CoStepHistory::STEP_WAITING_UPDATE_RECEIPT_N1;
                            break;
                        case 1:
                            $step = CoStepHistory::STEP_WAITING_UPDATE_RECEIPT_N2;
                            break; 
                        case 2:
                            $step = CoStepHistory::STEP_WAITING_UPDATE_RECEIPT_N3;
                            break;
                        case 3:
                            $step = CoStepHistory::STEP_WAITING_UPDATE_RECEIPT_N4;
                            break;
                    }
                }
                break;
            case 'request':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_REQUEST;
                }
                else if ($action == CoStepHistory::ACTION_CREATE_PRICE_SURVEY) {
                    $step = CoStepHistory::STEP_CREATE_PRICE_SURVEY;
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    $step = CoStepHistory::STEP_WAITING_APPROVE_REQUEST;
                } else if ($action == CoStepHistory::ACTION_APPROVE_PRICE_SURVEY) {
                    $step = CoStepHistory::STEP_WAITING_APPROVE_PRICE_SURVEY;
                }
            case 'check_warehouse':
                if ($action == CoStepHistory::ACTION_SELECT) {
                    $step = CoStepHistory::STEP_CHECKWAREHOUSE;
                }
                break;
            case 'payment':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    switch ($stepId) {
                        case 0:
                            // Nếu phiếu chi lần 1 == 0 thì chuyển lần 2
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_PAYMENT_N1;
                            } else {
                                $this->insertNextStep('payment', $coId, $objectId, CoStepHistory::ACTION_CREATE, $stepId + 1);
                            }
                            break;
                        case 1:
                            // Nếu phiếu chi lần 2 == 0 thì chuyển lần 3
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_PAYMENT_N2;
                            } else {
                                $this->insertNextStep('payment', $coId, $objectId, CoStepHistory::ACTION_CREATE, $stepId + 1);
                            }
                            break;
                        case 2:
                            // Nếu phiếu chi lần 3 == 0 thì chuyển nhập kho
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_PAYMENT_N3;
                            } else {
                                $this->insertNextStep('warehouse-receipt', $coId, $objectId, CoStepHistory::ACTION_CREATE);
                            }
                            break;
                        case 3:
                            // Nếu phiếu chi lần 4 == 0 thì chuyển xuất kho
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = CoStepHistory::STEP_CREATE_PAYMENT_N4;
                            } else {
                                $this->insertNextStep('warehouse-export', $coId, $coId, CoStepHistory::ACTION_CREATE);
                            }
                            break;
                    }
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    switch ($stepId) {
                        case 0:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N1;
                            break;
                        case 1:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N2;
                            break;
                        case 2:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N3;
                            break;
                        case 3:
                            $step = CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N4;
                            break;
                    }
                }
                break;
            case 'warehouse-receipt':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_WAREHOUSE_RECEIPT;
                }
                break;
            case 'warehouse-export':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT;
                }
                break;
            case 'manufacture':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_MANUFACTURE;
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    $step = CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE;
                }
                break;
            case 'delivery':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_DELIVERY;
                } else if ($action == CoStepHistory::ACTION_APPROVE) {
                    $step = CoStepHistory::STEP_WAITING_APPROVE_DELIVERY;
                }
                break;
            case 'warehouse-export-sell':
                if ($action == CoStepHistory::ACTION_CREATE) {
                    $step = CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT_SELL;
                }
                break;
            case 'qc-check':
                switch ($stepId) {
                    case 1:
                        $step = CoStepHistory::STEP_WAITING_APPROVE_QC;
                        break;
                    case 2:
                        $step = CoStepHistory::STEP_CREATE_RECEIPT_N2;
                        break;
                    case 3:
                        $step = CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE;
                        break;
                    case 4: 
                        $step = CoStepHistory::STEP_CHECKWAREHOUSE;
                }
                break;
            default:
                break;
        }
        if ($step) {
            $params['step'] = $step;
            $this->insert($params);
        }
    }
}
