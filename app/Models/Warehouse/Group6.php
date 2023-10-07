<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group6 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'mo_ta',
        'cho_may_moc_thiet_bi',
        'sl_cai',
        'so_hopdong_hoadon',
        'ghi_chu',
        'date',
        'ton_sl_cai',
    ];

    protected $attributes = [
        'model_type' => WarehouhseHelper::PHU_TUNG_DUNG_CU,
    ];
}
