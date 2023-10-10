<?php

namespace App\Models\Repositories;

use App\Helpers\AdminHelper;
use App\Helpers\WarehouseHelper;
use App\Models\WarehouseSpws\WarehouseFiller;
use App\Models\WarehouseSpws\WarehouseGlandpackinglatty;
use App\Models\WarehouseSpws\WarehouseHoop;
use App\Models\WarehouseSpws\WarehouseOring;
use App\Models\WarehouseSpws\WarehousePtfeenvelope;
use App\Models\WarehouseSpws\WarehousePtfetape;
use App\Models\WarehouseSpws\WarehouseRtj;
use App\Models\WarehouseSpws\WarehouseThanhphamswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhinnerswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhouterswg;

class WarehouseSpwRepository
{
    protected $warehouseFiller;
    protected $warehouseGlandpackinglatty;
    protected $warehouseHoop;
    protected $warehouseOring;
    protected $warehousePtfeenvelope;
    protected $warehousePtfetape;
    protected $warehouseRtj;
    protected $warehouseThanhphamswg;
    protected $warehouseVanhtinhinnerswg;
    protected $warehouseVanhtinhouterswg;

    public function __construct(
        WarehouseFiller $warehouseFiller,
        WarehouseGlandpackinglatty $warehouseGlandpackinglatty,
        WarehouseHoop $warehouseHoop,
        WarehouseOring $warehouseOring,
        WarehousePtfeenvelope $warehousePtfeenvelope,
        WarehousePtfetape $warehousePtfetape,
        WarehouseRtj $warehouseRtj,
        WarehouseThanhphamswg $warehouseThanhphamswg,
        WarehouseVanhtinhinnerswg $warehouseVanhtinhinnerswg,
        WarehouseVanhtinhouterswg $warehouseVanhtinhouterswg
    ) {
        $this->warehouseFiller            = $warehouseFiller;
        $this->warehouseGlandpackinglatty = $warehouseGlandpackinglatty;
        $this->warehouseHoop              = $warehouseHoop;
        $this->warehouseOring             = $warehouseOring;
        $this->warehousePtfeenvelope      = $warehousePtfeenvelope;
        $this->warehousePtfetape          = $warehousePtfetape;
        $this->warehouseRtj               = $warehouseRtj;
        $this->warehouseThanhphamswg      = $warehouseThanhphamswg;
        $this->warehouseVanhtinhinnerswg  = $warehouseVanhtinhinnerswg;
        $this->warehouseVanhtinhouterswg  = $warehouseVanhtinhouterswg;
    }

    public function getWarehouseSpws($model='filler', $where=array(), $opts=array()) 
    {
        $modelWarehouse = $this->getModel($model);
        if ($modelWarehouse !== false) {
            if ($model === 'thanhphamswg') {
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
                case 'filler':
                    $data["model_type"] = WarehouseHelper::FILLER;
                    $create = WarehouseHelper::getModel(WarehouseHelper::FILLER)::create($data);
                    break;
                case 'glandpackinglatty':
                    $data["model_type"] = WarehouseHelper::GLAND_PACKING_LATTY;
                    $create = WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING_LATTY)::create($data);
                    break;
                case 'hoop':
                    $data["model_type"] = WarehouseHelper::HOOP;
                    $create = WarehouseHelper::getModel(WarehouseHelper::HOOP)::create($data);
                    break;
                case 'oring':
                    $data["model_type"] = WarehouseHelper::ORING;
                    $create = WarehouseHelper::getModel(WarehouseHelper::ORING)::create($data);
                    break;
                case 'ptfeenvelope':
                    $data["model_type"] = WarehouseHelper::PTFE_ENVELOP;
                    $create = WarehouseHelper::getModel(WarehouseHelper::PTFE_ENVELOP)::create($data);
                    break;
                case 'ptfetape':
                    $data["model_type"] = WarehouseHelper::PTFE_TAPE;
                    $create = WarehouseHelper::getModel(WarehouseHelper::PTFE_TAPE)::create($data);
                    break;
                case 'rtj':
                    $data["model_type"] = WarehouseHelper::RTJ;
                    $create = WarehouseHelper::getModel(WarehouseHelper::RTJ)::create($data);
                    break;
                case 'thanhphamswg':
                    $data["model_type"] = WarehouseHelper::THANH_PHAM_SWG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::THANH_PHAM_SWG)::create($data);
                    break;
                case 'vanhtinhinnerswg':
                    $data["model_type"] = WarehouseHelper::VANH_TINH_INNER_SWG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_INNER_SWG)::create($data);
                    break;
                case 'vanhtinhouterswg':
                    $data["model_type"] = WarehouseHelper::VANH_TINH_OUTER_SWG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_OUTER_SWG)::create($data);
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
            case 'filler':
                $data = $this->warehouseFiller;
                break;
            case 'glandpackinglatty':
                $data = $this->warehouseGlandpackinglatty;
                break;
            case 'hoop':
                $data = $this->warehouseHoop;
                break;
            case 'oring':
                $data = $this->warehouseOring;
                break;
            case 'ptfeenvelope':
                $data = $this->warehousePtfeenvelope;
                break;
            case 'ptfetape':
                $data = $this->warehousePtfetape;
                break;
            case 'rtj':
                $data = $this->warehouseRtj;
                break;
            case 'thanhphamswg':
                $data = $this->warehouseThanhphamswg;
                break;
            case 'vanhtinhinnerswg':
                $data = $this->warehouseVanhtinhinnerswg;
                break;
            case 'vanhtinhouterswg':
                $data = $this->warehouseVanhtinhouterswg;
                break;
            default:
                $data = false;
        }
        return $data;
    }
}
