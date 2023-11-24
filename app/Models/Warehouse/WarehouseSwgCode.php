<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseSwgCode extends Model
{
    protected $fillable = [
        'code_part',
        'inner',
        'outer',
    ];

    protected $table = 'warehouse_swg_codes';
}
