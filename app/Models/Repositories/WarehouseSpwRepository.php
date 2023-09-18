<?php

namespace App\Models\Repositories;

use App\Helpers\AdminHelper;
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
                    $create = WarehouseFiller::create($data);
                    break;
                case 'glandpackinglatty':
                    $create = WarehouseGlandpackinglatty::create($data);
                    break;
                case 'hoop':
                    $create = WarehouseHoop::create($data);
                    break;
                case 'oring':
                    $create = WarehouseOring::create($data);
                    break;
                case 'ptfeenvelope':
                    $create = WarehousePtfeenvelope::create($data);
                    break;
                case 'ptfetape':
                    $create = WarehousePtfetape::create($data);
                    break;
                case 'rtj':
                    $create = WarehouseRtj::create($data);
                    break;
                case 'thanhphamswg':
                    $create = WarehouseThanhphamswg::create($data);
                    break;
                case 'vanhtinhinnerswg':
                    $create = WarehouseVanhtinhinnerswg::create($data);
                    break;
                case 'vanhtinhouterswg':
                    $create = WarehouseVanhtinhouterswg::create($data);
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
