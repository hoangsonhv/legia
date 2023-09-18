<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseHistory extends Model
{
    const TYPE_WAREHOUSE_RECEIPT = 0;
    const TYPE_WAREHOUSE_EXPORT = 1;
    protected $fillable = [
        'object_id',
        'type',
        'remaining',
        'quantity'
    ];
}
