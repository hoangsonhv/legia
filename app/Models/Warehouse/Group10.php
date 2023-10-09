<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group10 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'm_cay',
        'sl_cay',
        'sl_m',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'ton_sl_cay',
        'ton_sl_m',
        'model_type'
    ];

    // protected $attributes = [
    //     'model_type' => WarehouhseHelper::PTFE_ENVELOP,
    // ];
}
