<?php

namespace App\Models\Repositories;

use App\Models\Co;
use App\Models\CoStepHistory;
use App\Models\Manufacture;
use App\Models\ManufactureDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Repositories\CoStepHistoryRepository;
use App\Models\Repositories\CoRepository;
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
            ->whereRaw('reality_quantity < need_quantity')
            ->get();
        if(!$query->count()) {
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
                $this->coStepHisRepo->insertNextStep('receipt', $model->co_id, $model->co_id, CoStepHistory::ACTION_CREATE, 1);
            }
        }
    }

    public function createByCo($co)
    {
        $warehouses = $co->warehouses;
        $dataInsertMetals = [];
        $dataInsertNonMetals = [];
        foreach ($warehouses as $index => $warehouse) {
            $row = [
                'offer_price_id' => $warehouse->id,
                'need_quantity' => $warehouse->so_luong,
                'reality_quantity' => 0
            ];
            if($warehouse->material_type == Manufacture::MATERIAL_TYPE_METAL) {
                $row['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
                array_push($dataInsertMetals, $row);
            }
            if($warehouse->material_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
                $row['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
                array_push($dataInsertNonMetals, $row);
            }
        }

        $input['co_id'] = $co->id;
        $input['is_completed'] = Manufacture::WAITING;
        $input['admin_id'] = $user = Session::get('login')->id;
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
