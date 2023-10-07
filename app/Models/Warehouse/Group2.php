<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group2 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'trong_luong_cuon',
        'm_cuon',
        'sl_cuon',
        'sl_kg',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'ton_sl_kg',
    ];
    protected $attributes = [
        'model_type' => WarehouhseHelper::FILLTER_GLANDPACKING_HOOP,
    ];
}
