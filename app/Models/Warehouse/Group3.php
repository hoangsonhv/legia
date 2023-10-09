<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group3 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'm_cuon',
        'sl_cuon',
        'sl_m',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'ton_sl_m',
        'model_type'
    ];
    // protected $attributes = [
    //     'model_type' => WarehouhseHelper::DAYCAOSU_SILICON_ONGGLASSEXPORT_DAYCREAMIC_PTFECAYONG_PTFETAPE,
    // ];
}
