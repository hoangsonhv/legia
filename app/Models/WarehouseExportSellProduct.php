<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExportSellProduct extends Model
{
    protected $table = 'warehouse_export_sell_product';

    protected $fillable = [
        'warehouse_export_sell_id',
        'code',
        'name',
        'unit',
        'quantity',
        'unit_price',
        'into_money',
        'merchandise_id'
    ];
}
