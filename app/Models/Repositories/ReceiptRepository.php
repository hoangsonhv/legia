<?php

namespace App\Models\Repositories;

use App\Models\Receipt;
use App\Models\Repositories\BaseRepository;

class ReceiptRepository extends BaseRepository
{
    protected $prefix_id = 'PT';

    public function __construct(Receipt $receipt)
    {
        $this->model = $receipt;
    }

    public function getReceipts($where=array(), $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'co_code',
                'code'
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
}
