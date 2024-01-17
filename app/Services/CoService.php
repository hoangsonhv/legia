<?php

namespace App\Services;

use App\Helpers\DataHelper;
use App\Helpers\WarehouseHelper;
use App\Models\CoStepHistory;
use App\Models\MerchandiseGroup;
use App\Models\MerchandiseGroupWareHouse;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use App\Models\Repositories\WarehousePlateRepository;
use App\Models\Repositories\WarehouseRemainRepository;
use App\Models\Repositories\WarehouseSpwRepository;
use App\Models\Warehouse;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Models\Warehouse\WarehouseSwgCode;
use App\Models\Warehouse\WarehouseSwgSize;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;

class CoService
{
    protected $warehousePlateRepository;
    protected $warehouseSpwRepository;
    protected $warehouseRemainRepository;
    protected $baseWarehouseRepository;

    function __construct(
        WarehousePlateRepository $warehousePlateRepository,
        WarehouseSpwRepository $warehouseSpwRepository,
        WarehouseRemainRepository $warehouseRemainRepository,
        BaseWarehouseRepository $baseWarehouseRepository
    ) {
        $this->warehousePlateRepository  = $warehousePlateRepository;
        $this->warehouseSpwRepository    = $warehouseSpwRepository;
        $this->warehouseRemainRepository = $warehouseRemainRepository;
        $this->baseWarehouseRepository   = $baseWarehouseRepository;
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

    public function searchProductMaterialsInWarehouses($code, $lot_no)
    {
        $results = collect([]);
        try {
            if ($code) {
                $base_warehouse = BaseWarehouseCommon::where('code', $code)->first();
                if ($base_warehouse == null) {
                    return $results;
                }

                $this->baseWarehouseRepository->setModel(WarehouseHelper::getModel($base_warehouse->model_type));
                
                $nonZeroConditions = WarehouseHelper::nonZeroWarehouseMerchandiseConditions();

                $merchandises = $this->baseWarehouseRepository->model
                    ->where('model_type' , $base_warehouse->model_type)
                    ->where('code', $code)
                    ->where(function($query) use ($nonZeroConditions) {
                        foreach ($nonZeroConditions as $cnd) {
                            $query = $query->orWhere($cnd[0], $cnd[1], $cnd[2]);
                        }
                        return $query;
                    });

                if ($lot_no != null && strlen($lot_no) > 0) {
                    $merchandises = $merchandises->where('lot_no', $lot_no);
                }

                return $merchandises->get();
            }
        } catch(\Exception $ex) {
            dd($ex);
        }

        return $results;
    }

    public function getProductMaterialsInWarehouses($codes)
    {
        $results = collect([]);
        try {
            if ($codes) {
                foreach ($codes as $code) {
                    $merchandiseCode = \App\Helpers\AdminHelper::detectProductCode($code);
                    if ($merchandiseCode['model_type'] == null
                        || $merchandiseCode['manufacture_type'] == MerchandiseGroup::COMMERCE) {
                        continue;
                    }

                    $query = '';
                    if ($merchandiseCode['model_type'] == WarehouseHelper::THANH_PHAM_SWG) {
                        $swg_material_codes = self::getSwgMaterialCodes($code);
                        foreach ($swg_material_codes as $model_id => $swg_material_code) {
                            if ($swg_material_code == '') continue;

                            $query = self::getMaterialsQuery($swg_material_code, $model_id);
                            $materials = WarehouseHelper::getModel($model_id)->hydrate($query->get()->toArray());
                            $results = $results->merge($materials);
                        }
                    }
                    else {
                        $group = MerchandiseGroup::where('code', 'like' , '%' . $merchandiseCode['merchandise_group_code'] . '%' )->first();
                        $group_model_types = $group->warehouses->pluck('model_type')->toArray();

                        $aCode = explode(' ', $code);
                        $material_code = $code;
                        if (isset($aCode[0]) && isset($aCode[1])) {
                            $material_code = trim(trim($aCode[0]) . ' '. trim($aCode[1]));
                        } else {
                            continue;
                        }

                        $query = self::getMaterialsQuery($material_code, $group_model_types);
                        $warehouse_materials = $query->get()->toArray();
                        if (count($warehouse_materials) > 0) {
                            $model_type = $warehouse_materials[0]['model_type'];
                            $materials = WarehouseHelper::getModel($model_type)->hydrate($warehouse_materials);
                            $results = $results->merge($materials);
                        }
                    }
                }

            }
        } catch(\Exception $ex) {
            dd($ex);
        }

        return $results;
    }

    private function getSwgMaterialCodes($swg_code) {
        $sub_in = substr($swg_code, 3, 1);
        $sub_out = substr($swg_code, 6, 1);
        $h_f_size = substr($swg_code, 8, 3);
        $rim_size = WarehouseSwgSize::where('code_size', substr($swg_code, 8, 3))
            ->select('rim_size')->pluck('rim_size')->first() ?? '';
        $last = WarehouseSwgCode::select(['inner', 'outer'])->where('code_part', substr($swg_code, -8))->first() ?? '';
        
        if ($last == '' || $rim_size == '') {
            $product_inner = '';
            $product_outer = '';
        }
        else
        {
            $product_inner = 'FD '.$sub_in.' '.$rim_size.' '.$last['inner'];
            $product_outer = 'FD '.$sub_out.' '.$rim_size.' '.$last['outer'];
        }

        if ($rim_size == '') {
            $material_inner = '';
            $material_outer = '';
        }
        else
        {
            $material_inner = 'RAW '.$sub_in.' '.$rim_size;
            $material_outer = 'RAW '.$sub_out.' '.$rim_size;
        }

        $hoop = 'H '.substr($swg_code, 4, 1).' '.$h_f_size;
        $filler = 'F '.substr($swg_code, 5, 1).' '.$h_f_size;

        return [
            WarehouseHelper::VANH_TINH_INNER_SWG => $product_inner,
            WarehouseHelper::VANH_TINH_OUTER_SWG => $product_outer,
            WarehouseHelper::TAM_KIM_LOAI => $material_inner,
            WarehouseHelper::TAM_KIM_LOAI => $material_outer,
            WarehouseHelper::HOOP => $hoop,
            WarehouseHelper::FILLER => $filler
        ];
    }

    private function getMaterialsQuery($code, $model_type) {
        $nonZeroConditions = WarehouseHelper::nonZeroWarehouseMerchandiseConditions();
        $query = BaseWarehouseCommon::where('code', 'like', '%'.$code.'%')
            ->where(function($query) use ($nonZeroConditions) {
                foreach ($nonZeroConditions as $cnd) {
                    $query = $query->orWhere($cnd[0], $cnd[1], $cnd[2]);
                }
                return $query;
            });

        if (is_array($model_type)) {
            $query = $query->whereIn('model_type', $model_type);
        }
        else
        {
            $query = $query->where('model_type', $model_type);
        }

        return $query;
    }

    // public function getProductMaterialsInWarehouses($codes, $ignoreZero)
    // {
    //     $results = collect([]);
    //     try {
    //         if ($codes) {
    //             $warehouse_ids = [];

    //             foreach ($codes as $code) {
    //                 $merchandiseCode = \App\Helpers\AdminHelper::detectProductCode($code);
    //                 if (empty($merchandiseCode['merchandise_group_code'])) {
    //                     continue;
    //                 }

    //                 $group = MerchandiseGroup::where('code', 'like' , '%' . $merchandiseCode['merchandise_group_code'] . '%' )->first();
    //                 $warehouse_ids = array_merge($warehouse_ids, $group->warehouses->pluck('id')->toArray());
    //             }
    //             $warehouse_ids = array_unique($warehouse_ids);
                
    //             $nonZeroConditions = WarehouseHelper::nonZeroWarehouseMerchandiseConditions();

    //             foreach ($warehouse_ids as $warehouse_id) {
    //                 $warehouse = Warehouse::find($warehouse_id);
    //                 $tonKhoKey = WarehouseHelper::groupTonKhoKey($warehouse->model_type);
    //                 $this->baseWarehouseRepository->setModel(WarehouseHelper::getModel($warehouse->model_type));
                    
    //                 $query = DB::table('base_warehouses')
    //                     ->select('*', DB::raw('sum('.$tonKhoKey.') as '.$tonKhoKey))
    //                     ->where('model_type' , $warehouse->model_type)
    //                     ->where(function($query) use ($nonZeroConditions) {
    //                         foreach ($nonZeroConditions as $cnd) {
    //                             $query = $query->orWhere($cnd[0], $cnd[1], $cnd[2]);
    //                         }
    //                         return $query;
    //                     })->groupBy('code');

    //                 $materials = $this->baseWarehouseRepository
    //                     ->model->hydrate($query->get()->toArray());
    //                 $results = $results->merge($materials);
    //             }
    //         }
    //     } catch(\Exception $ex) {
    //         dd($ex);
    //     }

    //     return $results;
    // }

    // public function getProductMaterialsInWarehouses($codes, $ignoreZero)
    // {
    //     $result = collect([]);
    //     try {
    //         if ($codes) {
    //             foreach ($codes as $code) {
    //                 $merchandiseCode = \App\Helpers\AdminHelper::detectProductCode($code);
    //                 if(empty($merchandiseCode['merchandise_group_code'])) {
    //                     continue;
    //                 }
    //                 $merchandiseGroup = MerchandiseGroup::where('code', 'like' , '%' . $merchandiseCode['merchandise_group_code'] . '%' )->first();
    //                 $mGroupWarehouses = $merchandiseGroup->warehouses;
    //                 $merchindiseWarehouse = collect([]);
    //                 foreach ($mGroupWarehouses as $warehouse) {
    //                     $this->baseWarehouseRepository->setModel(WarehouseHelper::getModel($warehouse->model_type));
    //                     $arrCode = explode(" ", strtoupper($code));
    //                     $codeInWareHouse = "";
    //                     foreach ($arrCode as $value) {
    //                         $codeInWareHouse = $codeInWareHouse ? $codeInWareHouse . ' ' . $value : $value;
    //                         $totalInWarehouse = $this->baseWarehouseRepository->model
    //                             ->where('code', $codeInWareHouse)
    //                             ->where('model_type' , $warehouse->model_type);

    //                         if ($ignoreZero == true) {
    //                             $conditions = WarehouseHelper::nonZeroWarehouseMerchandiseConditions();

    //                             $totalInWarehouse = $totalInWarehouse->where(function($query) use ($conditions) {
    //                                 foreach ($conditions as $cnd) {
    //                                     $query = $query->orWhere($cnd[0], $cnd[1], $cnd[2]);
    //                                 }
    //                                 return $query;
    //                             });
    //                         }
    //                         $totalInWarehouse = $totalInWarehouse->get(); 

    //                         if(count($totalInWarehouse)) {
    //                             $merchindiseWarehouse = $merchindiseWarehouse->merge($totalInWarehouse)->unique();
    //                             continue;
    //                         }
    //                     }
    //                     $result = $result->merge($merchindiseWarehouse);
    //                 }
    //             }
    //         }
    //     } catch(\Exception $ex) {
    //         dd($ex);
    //     }
    //     return $result;
    // }

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

    public function getFullPlateWareHouses($where=array()) {
        $warehouses = DataHelper::getModelWarehouses('plate');
        $results = new Collection();
        if ($warehouses) {
            foreach($warehouses as $kSubWarehouse => $vSubWarehouse) {
                //($this->warehousePlateRepository->getWarehousePlates($kSubWarehouse, $where));
                $results = $results->merge($this->warehousePlateRepository->getWarehousePlates($kSubWarehouse, $where)->get());
            }
        }
        return $results;
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
            CoStepHistory::STEP_CREATE_PRICE_SURVEY => [
                'title' => 'B5. Đang chờ tạo khảo sát giá',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PRICE_SURVEY => [
                'title' => 'B6. Đang chờ duyệt khảo sát giá',
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N1 => [
                'title' => 'B7. Đang chờ tạo phiếu chi '. self::paymentInportStep()[0],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N1 => [
                'title' => 'B8. Đang chờ duyệt phiếu chi '. self::paymentInportStep()[0],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N2 => [
                'title' => 'B9. Đang chờ tạo phiếu chi '. self::paymentInportStep()[1],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N2 => [
                'title' => 'B10. Đang chờ duyệt phiếu chi '. self::paymentInportStep()[1],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N3 => [
                'title' => 'B11. Đang chờ tạo phiếu chi '. self::paymentInportStep()[2],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N3 => [
                'title' => 'B12. Đang chờ duyệt phiếu chi '. self::paymentInportStep()[2],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.payment.edit'
            ],
            CoStepHistory::STEP_CREATE_PAYMENT_N4 => [
                'title' => 'B13. Đang chờ tạo phiếu chi '. self::paymentInportStep()[3],
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.request.edit'
            ],
            CoStepHistory::STEP_WAITING_APPROVE_PAYMENT_N4 => [
                'title' => 'B14. Đang chờ duyệt phiếu chi '. self::paymentInportStep()[3],
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
                'title' => 'B20. Đang chờ tạo phiếu xuất kho bán hàng',
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
            CoStepHistory::STEP_CREATE_DELIVERY => [
                'title' => 'B23. Đang chờ tạo giao hàng',
                'action' => CoStepHistory::ACTION_CREATE,
                'act_router' => 'admin.co.edit',
            ],
            CoStepHistory::STEP_WAITING_APPROVE_RECEIPT_N3 => [
                'title' => 'B24. Đang chờ duyệt phiếu thu '. self::paymentStep()[2],
                'action' => CoStepHistory::ACTION_APPROVE,
                'act_router' => 'admin.receipt.edit',
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
    public static function paymentInportStep()
    {
        return [
            'Trước khi mua hàng',
            'Trước khi giao hàng',
            'Ngay khi giao hàng',
            'Sau khi giao hàng và chứng từ thanh toán'
        ];
    }
}
