<?php

namespace App\Models\Repositories;

use App\Models\PriceSurvey;
use App\Models\Supplier;
use App\Models\SupplierProduct;

class PriceSurveyRepository extends AdminRepository
{
    protected $model;

    public function __construct(PriceSurvey $priceSurvey)
    {
        $this->model = $priceSurvey;
    }

    public function search($where=array(), $opts=array())
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'product_group',
            ];
            foreach ($where as $kWhere=>$vWhere)
            {
                if($vWhere) {
                    //Search with product_code/product_name/supplier_name
                    $checkSuppliers = Supplier::query()
                        ->with('product')
                        ->orWhereHas('product', function ($query) use ($vWhere) {
                            $query->where('attribute->code', 'like', "%$vWhere%")
                                ->orWhere('attribute->mo_ta', 'like', "%$vWhere%");
                        })
                        ->get();

                    if($checkSuppliers) {
                        $list_supplier_ids = $checkSuppliers->pluck('id')->toArray();
                        return $query->where('supplier', 'like', "%$vWhere%")->orWhereIn('supplier_id', $list_supplier_ids);
                    }
                }

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
