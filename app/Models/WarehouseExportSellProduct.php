<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExportSellProduct extends Model
{
    protected $table = 'warehouse_export_sell_product';

    protected $fillable = [
        'warehouse_export_sell_id',
        'code',
        'do_day',
        'tieu_chuan',
        'kich_co',
        'kich_thuoc',
        'chuan_bich',
        'chuan_gasket',
        'name',
        'vat',
        'unit',
        'quantity',
        'unit_price',
        'lot_no',
        'into_money',
        'merchandise_id'
    ];
}
