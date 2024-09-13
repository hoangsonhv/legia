<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseReceiptProduct extends Model
{
    protected $fillable = [
        'warehouse_receipt_id',
        'code',
        'do_day',
        'hinh_dang',
        'dia_w_w1',
        'l_l1',
        'w2',
        'l2',
        'name',
        'unit',
        'quantity_doc',
        'quantity_reality',
        'unit_price',
        'lot_no',
        'kich_thuoc',
        'quy_cach',
        'into_money',
        'merchandise_id'
    ];
}
