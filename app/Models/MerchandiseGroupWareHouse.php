<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseGroupWareHouse extends Model
{
    protected $table = "merchandise_group_warehouse";
    protected $fillable = [
        'merchandise_group_id',
        'warehouse_id'
    ];
}
