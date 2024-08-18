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
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
        ];
    }
    public function getDvTinhAttribute() {
        return 'Cái';
    }
    protected $table = 'warehouse_swg_codes';
}
