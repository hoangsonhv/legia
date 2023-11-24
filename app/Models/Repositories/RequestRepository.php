<?php

namespace App\Models\Repositories;

use App\Helpers\DataHelper;
use App\Models\PriceSurvey;
use App\Models\Repositories\BaseRepository;
use App\Models\Request;

class RequestRepository extends BaseRepository
{
    protected $prefix_id = 'RFQ';

    public function __construct(Request $payment)
    {
        $this->model = $payment;
    }

    public function getRequests($where=array(), $opts=array()) 
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

    public function checkQuantityMaterial($repository)
    {
        if(!$repository) {
            return false;
        }
        $materials = $repository->material;
        $flag = true;
        foreach ($materials as $material) {
            if(!$material->dinh_luong) {
                $flag = false;
            }
        }
        return $flag;
    }

    public function checkConditionApproved($repository)
    {
        if($repository && $repository->money_total) {
            $thanhToan = $repository->thanh_toan;
            if($thanhToan) {
                $percent = is_array($thanhToan['percent']) ? $thanhToan['percent'] : [];
                if($percent) {
                    if(($percent['truoc_khi_lam_hang'] + $percent['truoc_khi_giao_hang'] + $percent['ngay_khi_giao_hang']
                            + $percent['sau_khi_giao_hang_va_cttt']) == 100) {
                        return true;
                    }
                }
            }
        }
        return false;
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

    public function checkBuyPriceSurvey($repository, &$message)
    {
        $message = '';
        if(!$repository->material->count()) {
            $message = 'Bạn chưa thực hiện khảo sát giá!';
            return false;
        }
        foreach ($repository->material as $material) {
            $priceSurvey = PriceSurvey::where('material_id', $material->id)
                ->where('request_id', $repository->id)
                ->where('status', PriceSurvey::TYPE_BUY)
                ->get();

            // if($priceSurvey->count() == 0) {
            //     $message = $material->code . ' chưa được nhà cung cấp';
            //     return false;
            // }

            if($priceSurvey->count() > 1) {
                $message = $material->code . ' không được chọn mua nhiều hơn 1 nhà cung cấp';
                return false;
            }
        }
        return true;
    }
}
