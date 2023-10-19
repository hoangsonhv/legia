<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class MerchandiseGroup extends Model
{
    const METAL = 1;
    const NON_METAL = 0;
    const MANUFACTURE = 1;
    const COMMERCE = 0;
    const FACTORY_TYPE = [
        self::METAL => 'Kim loại',
        self::NON_METAL => 'Phi kim loại'
    ];
    const OPERATION_TYPE = [
        self::MANUFACTURE => 'Sản xuất',
        self::COMMERCE => 'Thương mại'
    ];
    protected $table = "merchandise_groups";
    protected $fillable = [
        'name',
        'code',
        'factory_type',
        'operation_type',
    ];
}
