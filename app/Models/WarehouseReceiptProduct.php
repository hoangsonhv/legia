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
        'into_money',
        'merchandise_id'
    ];
}
