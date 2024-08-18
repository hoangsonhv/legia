<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseSwgSize extends Model
{
    protected $fillable = [
        'code_size',
        'rim_size',
    ];
    public function getDetailAttribute() {
        return [
        ];
    }
    protected $table = 'warehouse_swg_sizes';
}
