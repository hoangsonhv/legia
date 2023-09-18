<?php

namespace App\Models\Repositories;

use App\Models\WarehouseReceipt;

class WarehouseReceiptRepository extends AdminRepository
{
    public function __construct(WarehouseReceipt $warehouseReceipt)
    {
        $this->model = $warehouseReceipt;
    }

    public function generateCode()
    {
        $maxId = ($this->model->max('id') ?? 0) + 1;
        $lenId = strlen($maxId);
        if($lenId < 7) {
            $id = '';
            for($i = 0; $i < (7 - $lenId); $i++){
                $id = $id . '0';
            }
        }
        $code = 'NK'.$id.$maxId;
        return $code;
    }

    public function findExtend($where=array(), $opts=array())
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'code',
                'delivery_name'
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
}
