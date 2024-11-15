<?php

namespace App\Models\Repositories;

use App\Helpers\DataHelper;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\ManufactureRepository;
use App\Models\Manufacture;
use App\Models\Request as RequestModel;
use App\Models\RequestStepHistory;

class RequestStepHistoryRepository extends AdminRepository
{
    protected $coRepo;
    protected $requestRepo;

    public function __construct(RequestStepHistory $requestStepHistory,
                                RequestRepository $requestRepo)
    {
        $this->model = $requestStepHistory;
        $this->requestRepo = $requestRepo;
    }

    public function insertNextStep($type, $requestId, $objectId, $action, $stepId = 0)
    {
        $params['request_id'] = $requestId;
        $params['object_type'] = $type;
        $params['object_id'] = $objectId;
        $requestModel = RequestModel::find($requestId);
        $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM]);
        $step = null;
        switch ($type) {
            case 'request':
                if ($action == RequestStepHistory::ACTION_CREATE) {
                    $step = RequestStepHistory::STEP_CREATE_REQUEST;
                }
                else if ($action == RequestStepHistory::ACTION_CREATE_PRICE_SURVEY) {
                    $step = RequestStepHistory::STEP_CREATE_PRICE_SURVEY;
                } else if ($action == RequestStepHistory::ACTION_APPROVE) {
                    $step = RequestStepHistory::STEP_WAITING_APPROVE_REQUEST;
                } else if ($action == RequestStepHistory::ACTION_APPROVE_PRICE_SURVEY) {
                    $step = RequestStepHistory::STEP_WAITING_APPROVE_PRICE_SURVEY;
                }
            case 'payment':
                if ($action == RequestStepHistory::ACTION_CREATE) {
                    switch ($stepId) {
                        case 0:
                            // Nếu phiếu chi lần 1 == 0 thì chuyển lần 2
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = RequestStepHistory::STEP_CREATE_PAYMENT_N1;
                            } else {
                                $this->insertNextStep('payment', $requestId, $objectId, RequestStepHistory::ACTION_CREATE, $stepId + 1);
                            }
                            break;
                        case 1:
                            // Nếu phiếu chi lần 2 == 0 thì chuyển lần 3
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = RequestStepHistory::STEP_CREATE_PAYMENT_N2;
                            } else {
                                $this->insertNextStep('payment', $requestId, $objectId, RequestStepHistory::ACTION_CREATE, $stepId + 1);
                            }
                            break;
                        case 2:
                            // Nếu phiếu chi lần 3 == 0 thì chuyển lần 4
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent) {
                                $step = RequestStepHistory::STEP_CREATE_PAYMENT_N3;
                            } else {
                                $this->insertNextStep('payment', $requestId, $objectId, RequestStepHistory::ACTION_CREATE, $stepId + 1);
                            }
                            break;
                        case 3:
                            // Nếu phiếu chi lần 4 == 0 thì chuyển nhập kho
                            $hasPercent = $this->requestRepo->checkPercentPayment($objectId, $stepId);
                            if ($hasPercent || in_array($requestModel->category, array_keys(DataHelper::getCategoriesForIndex([DataHelper::HOAT_DONG, DataHelper::DINH_KY])))) {
                                $step = RequestStepHistory::STEP_CREATE_PAYMENT_N4;
                            } else if(in_array($requestModel->category, array_keys($categories))) {
                                $this->insertNextStep('warehouse-receipt', $requestId, $objectId, RequestStepHistory::ACTION_CREATE);
                            }
                            break;
                    }
                } else if ($action == RequestStepHistory::ACTION_APPROVE) {
                    switch ($stepId) {
                        case 0:
                            $step = RequestStepHistory::STEP_WAITING_APPROVE_PAYMENT_N1;
                            break;
                        case 1:
                            $step = RequestStepHistory::STEP_WAITING_APPROVE_PAYMENT_N2;
                            break;
                        case 2:
                            $step = RequestStepHistory::STEP_WAITING_APPROVE_PAYMENT_N3;
                            break;
                        case 3:
                            $step = RequestStepHistory::STEP_WAITING_APPROVE_PAYMENT_N4;
                            break;
                    }
                }
                break;
            case 'warehouse-receipt':
                if ($action == RequestStepHistory::ACTION_CREATE) {
                    $step = RequestStepHistory::STEP_CREATE_WAREHOUSE_RECEIPT;
                }
                break;
            case 'warehouse-export':
                if ($action == RequestStepHistory::ACTION_CREATE) {
                    $step = RequestStepHistory::STEP_CREATE_WAREHOUSE_EXPORT;
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
