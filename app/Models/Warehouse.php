<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = "warehouses";
    protected $fillable = [
        'name',
        'model_type'
    ];

    public function groups() {
        return $this->belongsToMany(MerchandiseGroup::class, 'merchandise_group_warehouse');
    }
}
