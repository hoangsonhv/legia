<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group8 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'sl_cuon',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
    ];
    protected $attributes = [
        'model_type' => WarehouhseHelper::GLAND_PACKING_LATTY,
    ];
}
