<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExportProduct extends Model
{
    protected $fillable = [
        'warehouse_export_id',
        'merchandise_id',
        'code',
        'do_day',
        'hinh_dang',
        'dia_w_w1',
        'l_l1',
        'w2',
        'l2',
        'name',
        'unit',
        'lot_no',
        'quantity_doc',
        'quantity_reality',
        'unit_price',
        'into_money'
    ];
}
