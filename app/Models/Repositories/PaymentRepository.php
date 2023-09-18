<?php

namespace App\Models\Repositories;

use App\Models\Payment;
use App\Models\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository
{
    protected $prefix_id = 'PC';

    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }

    public function getPayments($where=array(), $opts=array()) 
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
                    } elseif ($condition === "notNull") {
                        $query = $query->whereNotNull($field);
                    } else if($condition === "between") {
                        $query = $query->whereBetween($field,$val);
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
