<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseSwgSize extends Model
{
    protected $fillable = [
        'code_size',
        'rim_size',
    ];

    protected $table = 'warehouse_swg_sizes';
}
