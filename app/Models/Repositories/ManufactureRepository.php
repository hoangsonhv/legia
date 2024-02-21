<?php

namespace App\Models\Repositories;

use App\Helpers\WarehouseHelper;
use App\Models\Co;
use App\Models\CoStepHistory;
use App\Models\Manufacture;
use App\Models\ManufactureDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\CoRepository;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Models\WarehouseGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ManufactureRepository extends AdminRepository
{
    protected $coStepHisRepo;
    protected $coRepo;
    public function __construct(Manufacture $manufacture,
                                CoStepHistoryRepository $coStepHisRepo,
                                CoRepository $coRepo)
    {
        $this->model = $manufacture;
        $this->coStepHisRepo = $coStepHisRepo;
        $this->coRepo = $coRepo;
    }

    public function findExtend($where=array(), $opts=array())
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'note',
            ];
            foreach ($where as $kWhere=>$vWhere)
            {
                if(is_array($vWhere) && list($field,$condition,$val) = $vWhere)
                {
                    if($condition == "like") {
                        $query = $query->where($field,$condition,"%".$val."%");
                    } else {
                        $query = $query->where($field,$condition,$val);
                    }
                } elseif(array_key_exists($kWhere, $or)) {
                    $orCond = $or[$kWhere];
                    $query = $query->where(function ($q) use ($orCond, $vWhere) {
                        foreach($orCond as $vOr) {
                            $q->orWhere($vOr, 'like', "%".$vWhere."%");
                        }
                    });
                } else {
                    $query = $query->where($kWhere,$vWhere);
                }
            }
        }

        return $query;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $id
     */
    public function updateIsCompleted($id)
    {
        $query = ManufactureDetail::where('manufacture_id', $id)
            ->whereRaw('reality_quantity < manufacture_quantity')
            ->get();
        
        if(!$query->count()) {
            $details = ManufactureDetail::where('manufacture_id', $id)->get();
            $date = date('Y-m-d');

            foreach ($details as $index => $detail) {
                $material = $detail->offerPrice;
                $modelAttributes = [
                    'code' => $material->code,
                    'vat_lieu'  => $material->loai_vat_lieu,
                    'do_day'    => $material->do_day,
                    'tieu_chuan' => $material->tieu_chuan,
                    'kich_co'   => $material->kich_co,
                    'kich_thuoc'    => $material->kich_thuoc,
                    'chuan_mat_bich'    => $material->chuan_bich,
                    'chuan_gasket'  => $material->chuan_gasket,
                    'dvt'   => $material->dv_tinh,
                    'lot_no' => $detail->lot_no,
                    'ghi_chu' => 'Đầu kỳ',
                    'date' => $date,
                    'model_type' => WarehouseHelper::PRODUCT_WAREHOUSES[$material->material_type],
                ];

                if ($detail->reality_quantity >= $material->need_quantity) {
                    $base_warehouse = BaseWarehouseCommon::where('code', $material->code)->first();
                    $warehouseModelId = 0;

                    if ($base_warehouse != null) {
                        $merchandise = WarehouseHelper::getModel($base_warehouse->model_type)
                            ->find($base_warehouse->l_id);

                        $new_merchandise = $merchandise->replicate();
                        $new_merchandise->created_at = Carbon::now();
                        $new_merchandise->ghi_chu = "";
                        $new_merchandise->lot_no = $detail->lot_no;
                        $new_merchandise->date = Carbon::now();
                        $new_merchandise->setQuantity($detail->reality_quantity, accumulate: false);
                        $new_merchandise->save();
                        $warehouseModelId = $new_merchandise->l_id;
                    }
                    else
                    {
                        $warehouseModel = WarehouseHelper::getModel(WarehouseHelper::PRODUCT_WAREHOUSES[$material->material_type])
                            ->create($modelAttributes);
                    
                        $warehouseModel->setQuantity($detail->reality_quantity, accumulate: false);
                        $warehouseModel->save();
                        $warehouseModelId = $warehouseModel->l_id;
                    }
                }

                $material->merchandise_id = $warehouseModelId;
                $material->save();
            }
            $this->update(['is_completed' => !$query->count() ? Manufacture::IS_COMPLETED : 0], $id);
        }
    }

    public function checkCompleted($id)
    {
        $model = $this->model->find($id);
        if($model && $model->is_completed) {
            $co = Co::find($model->co_id);
            $modelDiff = $this->model->where('co_id', $model->co_id)
                ->where('material_type', $model->material_type == Manufacture::MATERIAL_TYPE_NON_METAL
                    ? Manufacture::MATERIAL_TYPE_METAL : Manufacture::MATERIAL_TYPE_NON_METAL)
                ->where('is_completed', Manufacture::IS_COMPLETED)
                ->first();
            if($modelDiff && $modelDiff->is_completed && $co->currentStep && $co->currentStep->step == CoStepHistory::STEP_WAITING_APPROVE_MANUFACTURE) {
                $this->coStepHisRepo->insertNextStep('qc-check', $model->co_id, $model->co_id, CoStepHistory::ACTION_APPROVE, 1);
            }
        }
    }

    public function createByCo($co)
    {
        $warehouses = $co->warehouses;
        $dataInsertMetals = [];
        $dataInsertNonMetals = [];
        $commerceProductMetals = [];
        $commerceProductNonMetals = [];

        foreach ($warehouses as $index => $warehouse) {
            // $modelAttributes = [
            //     'code' => $warehouse->code,
            //     'vat_lieu'  => $warehouse->loai_vat_lieu,
            //     'do_day'    => $warehouse->do_day,
            //     'tieu_chuan' => $warehouse->tieu_chuan,
            //     'kich_co'   => $warehouse->kich_co,
            //     'kich_thuoc'    => $warehouse->kich_thuoc,
            //     'chuan_mat_bich'    => $warehouse->chuan_bich,
            //     'chuan_gasket'  => $warehouse->chuan_gasket,
            //     'dvt'   => $warehouse->dv_tinh,
            //     'model_type' => WarehouseHelper::PRODUCT_WAREHOUSES[$warehouse->material_type],
            // ];

            $row = [
                'offer_price_id' => $warehouse->id,
                'need_quantity' => $warehouse->so_luong,
                'reality_quantity' => 0,
                'manufacture_quantity' => $warehouse->so_luong_san_xuat,
                'lot_no' => $co->raw_code,
            ];

            if($warehouse->material_type == Manufacture::MATERIAL_TYPE_METAL) {
                $row['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
            }

            if($warehouse->material_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
                $row['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
            }

            if ($warehouse->manufacture_type == WarehouseGroup::TYPE_COMMERCE) {
                if($warehouse->material_type == Manufacture::MATERIAL_TYPE_METAL) {
                    array_push($commerceProductMetals, $row);
                }

                if($warehouse->material_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
                    array_push($commerceProductNonMetals, $row);
                }
                
                continue;
            }
            else {
                if($warehouse->material_type == Manufacture::MATERIAL_TYPE_METAL) {
                    array_push($dataInsertMetals, $row);
                }

                if($warehouse->material_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
                    array_push($dataInsertNonMetals, $row);
                }
            }
        }

        // Manufacture product
        $input = [];
        $input['co_id'] = $co->id;
        $input['is_completed'] = Manufacture::WAITING;
        $input['admin_id'] = Session::get('login')->id;
        $input['manufacture_type'] = WarehouseGroup::TYPE_MANUFACTURE;
        $input['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
        $modelMetal = Manufacture::create($input);

        if($modelMetal) {
            $modelMetal->details()->createMany($dataInsertMetals);
        }

        $input['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
        $modelNonMetal = Manufacture::create($input);
        if($modelNonMetal) {
            $modelNonMetal->details()->createMany($dataInsertNonMetals);
        }

        // Commerce product
        $input['is_completed'] = Manufacture::IS_COMPLETED;
        $input['manufacture_type'] = WarehouseGroup::TYPE_COMMERCE;
        $input['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
        $modelCommerceMetals = Manufacture::create($input);

        if($modelCommerceMetals) {
            $modelCommerceMetals->details()->createMany($commerceProductMetals);
        }
        
        $input['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
        $modelCommerceNonMetals = Manufacture::create($input);

        if($modelCommerceNonMetals) {
            $modelCommerceNonMetals->details()->createMany($commerceProductNonMetals);
        }
    }

    /**
     * Check need quantity
     * @param $coId
     */
    public function checkNeedQuantity($coId)
    {
        $manufactures = $this->model->where('co_id', $coId)
            ->with('details')->get();
        if($manufactures) {
            foreach ($manufactures as $manufacture){
                if(!$manufacture->details->count()) {
                    $manufacture->is_completed = Manufacture::IS_COMPLETED;
                    $manufacture->save();
                }
            }
        }
    }
}
