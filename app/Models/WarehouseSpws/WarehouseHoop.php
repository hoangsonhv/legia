<?php

namespace App\Models\WarehouseSpws;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehouseHoop extends Model
{
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'trong_luong_kg_cuon',
        'm_cuon',
        'sl_cuon',
        'sl_kg',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'ton_sl_kg',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    public function getDateAttribute($value) {
        return AdminHelper::convertDate($value);
    }

    public function co() {
        return $this->morphToMany(Co::class, 'co_warehouseables');
    }
}
