<?php

namespace App\Services;

use App\Helpers\DataHelper;
use App\Models\CoStepHistory;
use App\Models\Repositories\WarehousePlateRepository;
use App\Models\Repositories\WarehouseRemainRepository;
use App\Models\Repositories\WarehouseSpwRepository;

class CoService
{
    protected $warehousePlateRepository;
    protected $warehouseSpwRepository;
    protected $warehouseRemainRepository;

    function __construct(
        WarehousePlateRepository $warehousePlateRepository,
        WarehouseSpwRepository $warehouseSpwRepository,
        WarehouseRemainRepository $warehouseRemainRepository
    ) {
        $this->warehousePlateRepository  = $warehousePlateRepository;
        $this->warehouseSpwRepository    = $warehouseSpwRepository;
        $this->warehouseRemainRepository = $warehouseRemainRepository;
    }

    public function getMaterialsInWarehouses($codes) {
        $result = collect([]);
        try {
            if ($codes) {
                $codes = array_map(function($val) {
                    $aVal = explode(' ', $val);
                    if (isset($aVal[0]) && isset($aVal[1])) {
                        return trim(trim($aVal[0]) . ' '. trim($aVal[1]));
                    } else {
                        return null;
                    }
                }, $codes);
                $codes = array_keys(array_flip(array_diff($codes, [null])));
                foreach ($codes as $code) {
                    $params = ['code' => $code];
                    $data   = $this->queryWareHouses(null, null, $params)->get();
                    if ($data->count()) {
                        $result = $result->merge($data);
                    } else {
                        // Set empty if exists 1 code NOT EXTSTS in warehouse
                        $result = collect([]);
                        break;
                    }
                }
            }
        } catch(\Exception $ex) {
            report($ex);
        }
        return $result;
    }

    public function getProductMaterialsInWarehouses($codes)
    {
        $result = collect([]);
        try {
            if ($codes) {
                foreach ($codes as $code) {
                    $params = ['code' => $code];
                    $dataWarehousePlate = $this->queryAllWarehouse('plate', null, $params);
                    if ($dataWarehousePlate->count()) {
                        $result = $result->merge($dataWarehousePlate);
                    }
                    $dataWarehouseSpw = $this->queryAllWarehouse('spw', null, $params);
                    if ($dataWarehouseSpw->count()) {
                        $result = $result->merge($dataWarehouseSpw);
                    }
                    $dataWarehouseRemain = $this->queryAllWarehouse('remain', null, $params);
                    if ($dataWarehouseRemain->count()) {
                        $result = $result->merge($dataWarehouseRemain);
                    }
                }
            }
        } catch(\Exception $ex) {
            report($ex);
        }
        return $result;
    }

    public function queryAllWarehouse($warehouse = null, $model = null, $where = array())
    {
        $result = collect([]);
        $warehouses = DataHelper::getModelWarehouses($warehouse, $model);
        if ($warehouse === 'plate') {
            foreach ($warehouses as $kSubWarehouse => $vSubWarehouse) {
                $response = $this->warehousePlateRepository->getWarehousePlates($kSubWarehouse, $where)->get();
                if ($response->count()) {
                    $result = $result->merge($response);
                }
            }
        }
        if ($warehouse === 'spw') {
            foreach ($warehouses as $kSubWarehouse => $vSubWarehouse) {
                $response = $this->warehouseSpwRepository->getWarehouseSpws($kSubWarehouse, $where)->get();
                if ($response->count()) {
                    $result = $result->merge($response);
                }
            }
        }
        if ($warehouse === 'remain') {
            foreach ($warehouses as $kSubWarehouse => $vSubWarehouse) {
                $response = $this->warehouseRemainRepository->getWarehouseRemains($kSubWarehouse, $where)->get();
                if ($response->count()) {
                    $result = $result->merge($response);
                }
            }
        }
        return $result;
    }

    public function queryWareHouses($warehouse=null, $model=null, $where=array()) {
        $warehouses = DataHelper::getModelWarehouses($warehouse, $model);
        if ($warehouses) {
            if (!$warehouse) {
                foreach ($warehouses as $kWarehouse => $vWarehouse) {
                    if ($kWarehouse === 'plate') {
                        foreach($vWarehouse as $kSubWarehouse => $vSubWarehouse) {
                            return $this->warehousePlateRepository->getWarehousePlates($kSubWarehouse, $where);
                        }
                    } else if ($kWarehouse === 'spw') {
                        foreach($vWarehouse as $kSubWarehouse => $vSubWarehouse) {
                            return $this->warehouseSpwRepository->getWarehouseSpws($kSubWarehouse, $where);
                        }
                    } else if ($kWarehouse === 'remain') {
                        foreach($vWarehouse as $kSubWarehouse => $vSubWarehouse) {
                            return $this->warehouseRemainRepository->getWarehouseRemains($kSubWarehouse, $where);
                        }
                    }
                }
            } else if (!$model) {
                if ($warehouse === 'plate') {
                    foreach($warehouses as $kSubWarehouse => $vSubWarehouse) {
                        return $this->warehousePlateRepository->getWarehousePlates($kSubWarehouse, $where);
                    }
                } else if ($warehouse === 'spw') {
                    foreach($warehouses as $kSubWarehouse => $vSubWarehouse) {
                        return $this->warehouseSpwRepository->getWarehouseSpws($kSubWarehouse, $where);
                    }
                } else if ($warehouse === 'remain') {
                    foreach($warehouses as $kSubWarehouse => $vSubWarehouse) {
                        return $this->warehouseRemainRepository->getWarehouseRemains($kSubWarehouse, $where);
                    }
                }
            } else {
                if ($warehouse === 'plate') {
                    return $this->warehousePlateRepository->getWarehousePlates($model, $where);
                } else if ($warehouse === 'spw') {
                    return $this->warehouseSpwRepository->getWarehouseSpws($model, $where);
                } else if ($warehouse === 'remain') {
                    return $this->warehouseRemainRepository->getWarehouseRemains($model, $where);
                }
            }
        }
        return false;
    }

    public static function stepCO()
    {
        return [
            CoStepHistory::STEP_WAITING_APPROVE_CO => [
                'title' => 'B1. Đang chờ duyệt CO',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.co.edit'
            ],
            CoStepHistory::STEP_CREATE_RECEIPT_N1 => [
                'title' => 'B2. Đang chờ tạo phiếu thu '. self::paymentStep()[0],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N1 => [
                'title' => 'B3. Đang chờ duyệt phiếu thu '. self::paymentStep()[0],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.receipt.edit'
            ],
            CoStepHistory::STEP_CHECKWAREHOUSE => [
                'title' => 'B3. Kiểm kho',
                'action' => 'select',
                'next_step' => null,
                'back_step' => null,
                'option' => [
                    'enough' => 'Đủ NVL',
                    'lack' => 'Thiếu NVL',
                ],
                'next_option_enough' => 3,
                'next_option_lack' => 4,
                'act_router' => 'admin.co.edit'
            ],
            CoStepHistory::STEP_CREATE_REQUEST => [
                'title' => 'B4. Đang chờ tạo phiếu yêu cầu',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_REQUEST => [
                'title' => 'B5. Đang chờ duyệt phiếu yêu cầu',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PRICE_SURVEY => [
                'title' => 'B6. Đang chờ duyệt khảo sát giá',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N1 => [
                'title' => 'B7. Đang chờ tạo phiếu chi '. self::paymentStep()[0],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N1 => [
                'title' => 'B8. Đang chờ duyệt phiếu chi '. self::paymentStep()[0],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N2 => [
                'title' => 'B9. Đang chờ tạo phiếu chi '. self::paymentStep()[1],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N2 => [
                'title' => 'B10. Đang chờ duyệt phiếu chi '. self::paymentStep()[1],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N3 => [
                'title' => 'B11. Đang chờ tạo phiếu chi '. self::paymentStep()[2],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N3 => [
                'title' => 'B12. Đang chờ duyệt phiếu chi '. self::paymentStep()[2],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N4 => [
                'title' => 'B13. Đang chờ tạo phiếu chi '. self::paymentStep()[3],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N4 => [
                'title' => 'B14. Đang chờ duyệt phiếu chi '. self::paymentStep()[3],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_WAREHOUSE_RECEIPT => [
                'title' => 'B15. Đang chờ tạo phiếu nhập kho',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT => [
                'title' => 'B16. Đang chờ tạo phiếu xuất kho',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_CREATE_MANUFACTURE => [
                'title' => 'B17. Đang chờ tạo sản xuất',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE => [
                'title' => 'B18. Đang sản xuất',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.manufacture.index',
            ],
            CoStepHistory::STEP_CREATE_RECEIPT_N2 => [
                'title' => 'B19. Đang chờ tạo phiếu thu '. self::paymentStep()[1],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_CREATE_WAREHOUSE_EXPORT_SELL => [
                'title' => '20. Đang chờ tạo phiếu xuất kho bán hàng',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N2 => [
                'title' => 'B21. Đang chờ duyệt phiếu thu '. self::paymentStep()[1],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.receipt.edit',
            ],
            CoStepHistory::STEP_CREATE_RECEIPT_N3 => [
                'title' => 'B22. Đang chờ tạo phiếu thu '. self::paymentStep()[2],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N3 => [
                'title' => 'B23. Đang chờ duyệt phiếu thu '. self::paymentStep()[2],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.receipt.edit',
            ],
            CoStepHistory::STEP_CREATE_DELIVERY => [
                'title' => 'B24. Đang chờ tạo giao hàng',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_DELIVERY => [
                'title' => 'B25. Đơn hàng đang vận chuyển',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.delivery.edit',
            ],
            CoStepHistory::STEP_CREATE_RECEIPT_N4 => [
                'title' => 'B26. KH đã nhận được hàng. Đang đợi phiếu thu '. self::paymentStep()[3],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N4 => [
                'title' => 'B27. Đang chờ duyệt phiếu thu '. self::paymentStep()[3],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.receipt.edit',
            ]
        ];
    }

    public static  function paymentDocuments()
    {
        return [
            'hoa_don' => 'Hoá đơn',
            'phieu_xuat_kho' => 'Phiếu xuất kho',
            'bien_ban_ban_giao' => 'Biên bản bàn giao',
            'bien_ban_nghiem_thu' => 'Biên bản nghiệm thu',
            'giay_bao_hanh' => 'Giấy bảo hành',
            'chung_nhan_xuat_xuong' => 'Chứng nhận xuất xưởng',
            'chung_chi_xuat_xu_vat_lieu' => 'Chứng chỉ xuất xứ vật liệu',
            'chung_chi_chat_luong_vat_lieu' => 'Chứng chỉ chất lượng vật liệu',
            'test_report_vat_lieu' => 'Test report vật liệu',
            'test_report_thuc_pham' => 'Test report thực phẩm'
        ];
    }

    public static function paymentStep()
    {
        return [
            'Trước khi làm hàng',
            'Trước khi giao hàng',
            'Ngay khi giao hàng',
            'Sau khi giao hàng và chứng từ thanh toán'
        ];
    }
}
