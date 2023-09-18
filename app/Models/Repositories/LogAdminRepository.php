<?php

namespace App\Models\Repositories;

use App\Models\LogAdmin;
use Illuminate\Support\Facades\DB;

class LogAdminRepository
{
    protected $model;

    public function __construct(LogAdmin $logAadmin)
    {
        $this->model = $logAadmin;
    }

    public function getLogAdmins($where, $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'admin_name',
                'ip',
                'link'
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

    public function find($id) {
        return $this->model->find($id);
    }
}
