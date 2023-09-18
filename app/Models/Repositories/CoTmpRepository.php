<?php

namespace App\Models\Repositories;

use App\Models\CoTmp;
use App\Models\Repositories\BaseRepository;

class CoTmpRepository extends BaseRepository
{
    protected $prefix_id = 'COTMP';

    public function __construct(CoTmp $co)
    {
        $this->model = $co;
    }

    public function getCoes($where=array(), $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'code',
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
        if (!empty($opts['has'])) {
            $query = $query->has($opts['has']);
        }
        if (!empty($opts['where_has'])) {
            $query = $query->wherehas($opts['where_has']['relat'], function (Builder $q) use ($opts) {
                foreach($opts['where_has']['condition'] as $key => $val) {
                    $q->where($key, '=', $val);
                }
            });
        }
        return $query;
    }

    public function find($id) 
    {
        return $this->model->find($id);
    }
}
