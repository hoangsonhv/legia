<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use App\Models\Warehouse\BaseWarehouseCommon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group1 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'hinh_dang',
        'dia_w_w1',
        'l_l1',
        'w2',
        'l2',
        'sl_tam',
        'sl_m2',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_tam',
        'ton_sl_m2',
    ];
    protected $attributes = [
        'model_type' => WarehouhseHelper::BIA_CAOSU_CAOSUVNZA_TAMKIMLOAI_CREAMIC_GRAPHITE_PFTE_TAMNHUA,
    ];
   
}
