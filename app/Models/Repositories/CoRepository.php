<?php

namespace App\Models\Repositories;

use App\Helpers\DataHelper;
use App\Models\Co;
use App\Models\Repositories\BaseRepository;

class CoRepository extends BaseRepository
{
    protected $prefix_id = 'CO';

    public function __construct(Co $co)
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

    public function getListCo($id=null) {
        return Co::with(['related.coables' => function($q) use($id) {
            if ($id) {
                $q->where('coable_id', '=', $id);
            }
        }]);
    }

    public function approvedEligibility($repository)
    {
        if(!$repository) {
            return false;
        }
        $thanhToan = $repository->thanh_toan;
        $percent = is_array($thanhToan['percent']) ? $thanhToan['percent'] : [];
        if(!count($percent)) {
            return false;
        }
        if(($percent['truoc_khi_lam_hang'] + $percent['truoc_khi_giao_hang'] + $percent['ngay_khi_giao_hang']
            + $percent['sau_khi_giao_hang_va_cttt']) != 100) {
            return false;
        }
        return true;
    }

    public function checkPercentPayment($repositoryId, $stepId)
    {
        $repository = $this->model->find($repositoryId);
        if(!$repository) {
            return;
        }
        $thanhToan = $repository->thanh_toan;
        if(!$thanhToan) {
            return;
        }
        $steps = DataHelper::stepPay();
        $field = isset($steps[$stepId]['field']) ? $steps[$stepId]['field'] : null;
        if($field) {
            return $thanhToan['percent'][$field] ? true : false;
        }
        return false;
    }

    public function doneCo($coId)
    {
        $co = $this->model->find($coId);
        if ($co) {
            $co->confirm_done = 1;
            $co->save();
        }
    }
}
