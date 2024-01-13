<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseReceiptProduct extends Model
{
    protected $fillable = [
        'warehouse_receipt_id',
        'code',
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
