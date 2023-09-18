<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProductCode extends Model
{
    protected $fillable = [
        'code',
        'name',
        'warehouse_group_id',
        'admin_id'
    ];

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function group() {
        return $this->hasOne(WarehouseGroup::class, 'id', 'warehouse_group_id');
    }
}
