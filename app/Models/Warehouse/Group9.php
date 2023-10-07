<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group9 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'std',
        'size',
        'od',
        'id',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'ton_sl_cai',
    ];

    protected $attributes = [
        'model_type' => WarehouhseHelper::CCDC,
    ];
}
