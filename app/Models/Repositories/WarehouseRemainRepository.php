<?php

namespace App\Models\Repositories;

use App\Helpers\AdminHelper;
use App\Helpers\WarehouseHelper;
use App\Models\WarehouseRemains\WarehouseCcdc;
use App\Models\WarehouseRemains\WarehouseDaycaosusilicone;
use App\Models\WarehouseRemains\WarehouseDayceramic;
use App\Models\WarehouseRemains\WarehouseGlandpacking;
use App\Models\WarehouseRemains\WarehouseNdloaikhac;
use App\Models\WarehouseRemains\WarehouseNhuakythuatcayong;
use App\Models\WarehouseRemains\WarehouseNkloaikhac;
use App\Models\WarehouseRemains\WarehouseOngglassepoxy;
use App\Models\WarehouseRemains\WarehousePhutungdungcu;
use App\Models\WarehouseRemains\WarehousePtfecayong;

class WarehouseRemainRepository
{
    protected $warehouseCcdc;
    protected $warehouseDaycaosusilicone;
    protected $warehouseDayceramic;
    protected $warehouseGlandpacking;
    protected $warehouseNhuakythuatcayong;
    protected $warehouseOngglassepoxy;
    protected $warehousePhutungdungcu;
    protected $warehousePtfecayong;
    protected $warehouseNdloaikhac;
    protected $warehouseNkloaikhac;

    public function __construct(
        WarehouseCcdc $warehouseCcdc,
        WarehouseDaycaosusilicone $warehouseDaycaosusilicone,
        WarehouseDayceramic $warehouseDayceramic,
        WarehouseGlandpacking $warehouseGlandpacking,
        WarehouseNhuakythuatcayong $warehouseNhuakythuatcayong,
        WarehouseOngglassepoxy $warehouseOngglassepoxy,
        WarehousePhutungdungcu $warehousePhutungdungcu,
        WarehousePtfecayong $warehousePtfecayong,
        WarehouseNdloaikhac $warehouseNdloaikhac,
        WarehouseNkloaikhac $warehouseNkloaikhac
    ) {
        $this->warehouseCcdc              = $warehouseCcdc;
        $this->warehouseDaycaosusilicone  = $warehouseDaycaosusilicone;
        $this->warehouseDayceramic        = $warehouseDayceramic;
        $this->warehouseGlandpacking      = $warehouseGlandpacking;
        $this->warehouseNhuakythuatcayong = $warehouseNhuakythuatcayong;
        $this->warehouseOngglassepoxy     = $warehouseOngglassepoxy;
        $this->warehousePhutungdungcu     = $warehousePhutungdungcu;
        $this->warehousePtfecayong        = $warehousePtfecayong;
        $this->warehouseNdloaikhac        = $warehouseNdloaikhac;
        $this->warehouseNkloaikhac        = $warehouseNkloaikhac;
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
                case 'ccdc':
                    $data["model_type"] = WarehouseHelper::CCDC;
                    $create = WarehouseHelper::getModel(WarehouseHelper::CCDC)::create($data);
                    break;
                case 'daycaosusilicone':
                    $data["model_type"] = WarehouseHelper::DAY_CAO_SU_VA_SILICON;
                    $create = WarehouseHelper::getModel(WarehouseHelper::DAY_CAO_SU_VA_SILICON)::create($data);
                    break;
                case 'dayceramic':
                    $data["model_type"] = WarehouseHelper::DAY_CREAMIC;
                    $create = WarehouseHelper::getModel(WarehouseHelper::DAY_CREAMIC)::create($data);
                    break;
                case 'glandpacking':
                    $data["model_type"] = WarehouseHelper::GLAND_PACKING;
                    $create = WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING)::create($data);
                    break;
                case 'nhuakythuatcayong':
                    $data["model_type"] = WarehouseHelper::NHU_KY_THUAT_CAY_ONG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::NHU_KY_THUAT_CAY_ONG)::create($data);
                    break;
                case 'ongglassepoxy':
                    $data["model_type"] = WarehouseHelper::ONG_GLASS_EXPOXY;
                    $create = WarehouseHelper::getModel(WarehouseHelper::ONG_GLASS_EXPOXY)::create($data);
                    break;
                case 'phutungdungcu':
                    $data["model_type"] = WarehouseHelper::PHU_TUNG_DUNG_CU;
                    $create = WarehouseHelper::getModel(WarehouseHelper::PHU_TUNG_DUNG_CU)::create($data);
                    break;
                case 'ptfecayong':
                    $data["model_type"] = WarehouseHelper::PTFE_CAYONG;
                    $create = WarehouseHelper::getModel(WarehouseHelper::PTFE_CAYONG)::create($data);
                    break;
                case 'ndloaikhac':
                    $data["model_type"] = WarehouseHelper::ND_LOAI_KHAC;
                    $create = WarehouseHelper::getModel(WarehouseHelper::ND_LOAI_KHAC)::create($data);
                    break;
                case 'nkloaikhac':
                    $data["model_type"] = WarehouseHelper::NK_LOAI_KHAC;
                    $create = WarehouseHelper::getModel(WarehouseHelper::NK_LOAI_KHAC)::create($data);
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
            case 'ccdc':
                $data = $this->warehouseCcdc;
                break;
            case 'daycaosusilicone':
                $data = $this->warehouseDaycaosusilicone;
                break;
            case 'dayceramic':
                $data = $this->warehouseDayceramic;
                break;
            case 'glandpacking':
                $data = $this->warehouseGlandpacking;
                break;
            case 'nhuakythuatcayong':
                $data = $this->warehouseNhuakythuatcayong;
                break;
            case 'ongglassepoxy':
                $data = $this->warehouseOngglassepoxy;
                break;
            case 'phutungdungcu':
                $data = $this->warehousePhutungdungcu;
                break;
            case 'ptfecayong':
                $data = $this->warehousePtfecayong;
                break;
            case 'ndloaikhac':
                $data = $this->warehouseNdloaikhac;
                break;
            case 'nkloaikhac':
                $data = $this->warehouseNkloaikhac;
                break;
            default:
                $data = false;
        }
        return $data;
    }
}
