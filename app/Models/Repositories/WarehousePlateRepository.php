<?php

namespace App\Models\Repositories;

use App\Helpers\AdminHelper;
use App\Models\WarehousePlates\WarehouseBia;
use App\Models\WarehousePlates\WarehouseCaosu;
use App\Models\WarehousePlates\WarehouseCaosuvnza;
use App\Models\WarehousePlates\WarehouseCeramic;
use App\Models\WarehousePlates\WarehouseGraphite;
use App\Models\WarehousePlates\WarehousePtfe;
use App\Models\WarehousePlates\WarehouseTamkimloai;
use App\Models\WarehousePlates\WarehouseTamnhua;

class WarehousePlateRepository
{
    protected $warehouseBia;
    protected $warehouseCaosuvnza;
    protected $warehouseCaosu;
    protected $warehouseCeramic;
    protected $warehouseGraphite;
    protected $warehousePtfe;
    protected $warehouseTamkimloai;
    protected $warehouseTamnhua;

    public function __construct(
        WarehouseBia $warehouseBia,
        WarehouseCaosuvnza $warehouseCaosuvnza,
        WarehouseCaosu $warehouseCaosu,
        WarehouseCeramic $warehouseCeramic,
        WarehouseGraphite $warehouseGraphite,
        WarehousePtfe $warehousePtfe,
        WarehouseTamkimloai $warehouseTamkimloai,
        WarehouseTamnhua $warehouseTamnhua
    ) {
        $this->warehouseBia        = $warehouseBia;
        $this->warehouseCaosuvnza  = $warehouseCaosuvnza;
        $this->warehouseCaosu      = $warehouseCaosu;
        $this->warehouseCeramic    = $warehouseCeramic;
        $this->warehouseGraphite   = $warehouseGraphite;
        $this->warehousePtfe       = $warehousePtfe;
        $this->warehouseTamkimloai = $warehouseTamkimloai;
        $this->warehouseTamnhua    = $warehouseTamnhua;
    }

    public function getWarehousePlates($model='bia', $where=array(), $opts=array()) 
    {
        $modelWarehouse = $this->getModel($model);
        if ($modelWarehouse !== false) {
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
                case 'bia':
                    $create = WarehouseBia::create($data);
                    break;
                case 'caosuvnza':
                    $create = WarehouseCaosuvnza::create($data);
                    break;
                case 'caosu':
                    $create = WarehouseCaosu::create($data);
                    break;
                case 'ceramic':
                    $create = WarehouseCeramic::create($data);
                    break;
                case 'graphite':
                    $create = WarehouseGraphite::create($data);
                    break;
                case 'ptfe':
                    $create = WarehousePtfe::create($data);
                    break;
                case 'tamkimloai':
                    $create = WarehouseTamkimloai::create($data);
                    break;
                case 'tamnhua':
                    $create = WarehouseTamnhua::create($data);
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
            $or['key_word'] = [
                'code',
                'vat_lieu'
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

    private function getModel($model) {
        switch ($model) {
            case 'bia':
                $data = $this->warehouseBia;
                break;
            case 'caosuvnza':
                $data = $this->warehouseCaosuvnza;
                break;
            case 'caosu':
                $data = $this->warehouseCaosu;
                break;
            case 'ceramic':
                $data = $this->warehouseCeramic;
                break;
            case 'graphite':
                $data = $this->warehouseGraphite;
                break;
            case 'ptfe':
                $data = $this->warehousePtfe;
                break;
            case 'tamkimloai':
                $data = $this->warehouseTamkimloai;
                break;
            case 'tamnhua':
                $data = $this->warehouseTamnhua;
                break;
            default:
                $data = false;
        }
        return $data;
    }
}
