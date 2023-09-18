<?php

namespace App\Models\Repositories;

use App\Models\Config;
use Illuminate\Support\Facades\DB;

class ConfigRepository
{
    protected $model;

    public function __construct(Config $config)
    {
        $this->model = $config;
    }

    public function getConfigs($where, $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'key',
                'value'
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
