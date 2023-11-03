<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExportProduct extends Model
{
    protected $fillable = [
        'warehouse_export_id',
        'merchandise_id',
        'code',
        'name',
        'unit',
        'quantity_doc',
        'quantity_reality',
        'unit_price',
        'into_money'
    ];
}
