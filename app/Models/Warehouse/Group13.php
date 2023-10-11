<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group13 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'd3',
        'd4',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];
}
