<?php

namespace App\Models\Repositories;

use App\Models\BankHistoryTransaction;
use Illuminate\Support\Facades\Session;

class BankHistoryTransactionRepository
{
    protected $model;

    public function __construct(BankHistoryTransaction $bankHistoryTransaction)
    {
        $this->model = $bankHistoryTransaction;
    }

    public function getBankHistoryTransactions($where=array(), $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'created_at',
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
