<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseWarehouseCommon extends Model
{
    use HasFactory;
    public $modelType = '';
    protected $table = 'base_warehouses';
    protected $primaryKey = "l_id";
    protected $fillable = [ 
        "l_id"
    ];
}
