<?php

namespace App\Models\Repositories;

use App\Helpers\AdminHelper;
use App\Helpers\WarehouseHelper;
use App\Models\WarehouseSupply\WarehouseSupply;

class WarehouseSupplyRepository
{
    protected $warehouseSupply;

    public function __construct(
        WarehouseSupply $warehouseSupply
    ) {
        $this->warehouseSupply              = $warehouseSupply;
    }

    public function getWarehouseRemains($model='filler', $where=array(), $opts=array()) 
    {
        $modelWarehouse = $this->getModel($model);
        if ($modelWarehouse !== false) {
            $exceptModel = ['ccdc', 'phutungdungcu'];
            if (in_array($model, $exceptModel)) {
                $opts['or_key_word'] = [
                    'code',
                ];
            }
            return $this->getQuery($modelWarehouse, $where, $opts);
        }
        return false;
    }

    public function find($model, $id)
    {
        $modelWarehouse = $this->getModel($model);
        if ($modelWarehouse !== false) {
            return $this->getQuery($modelWarehouse, ['id' => $id])->first();
        }
        return false;
    }

    public function store($model, $data)
    {
        $create = false;
        try {
            if (!empty($data['date'])) {
                $data['date'] = AdminHelper::convertDate($data['date']);
            } else {
                $data['date'] = null;
            }
            switch ($model) {
                
                case 'supply':
                    $data["model_type"] = WarehouseHelper::KHO_VAT_DUNG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::KHO_VAT_DUNG)::create($data);
                    break;
            }
        } catch(\Exception $ex) {
            report($ex);
        }
        return $create;
    }

    public function update($model, $data, $warehousePlate)
    {
        try {
            $fillable = $this->getModel($model)->getFillable();
            if ($fillable === false) {
                throw new \Exception('Model không tồn tại.');
            }
            if (!empty($data['date'])) {
                $data['date'] = AdminHelper::convertDate($data['date']);
            } else {
                $data['date'] = null;
            }
            foreach($fillable as $field) {
                if (isset($data[$field])) {
                    $warehousePlate->{$field} = $data[$field];
                }
            }
            return $warehousePlate->save();
        } catch(\Exception $ex) {
            report($ex);
        }
        return false;
    }

    private function getQuery($query, $where=array(), $opts=array()) {
        if($where)
        {
            if (empty($opts['or_key_word'])) {
                $or['key_word'] = [
                    'code',
                    'vat_lieu'
                ];
            } else {
                $or['key_word'] = $opts['or_key_word'];
            }
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

    private function getModel($model) {
        switch ($model) {
            case 'supply':
                $data = $this->warehouseSupply;
                break;
            default:
                $data = false;
        }
        return $data;
    }
}
