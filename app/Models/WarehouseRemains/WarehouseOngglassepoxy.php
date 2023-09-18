<?php

namespace App\Models\WarehouseRemains;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehouseOngglassepoxy extends Model
{
    protected $table    = 'warehouse_ongglassepoxys';
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'm_cay',
        'sl_cay',
        'sl_m',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cay',
        'ton_sl_m',
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
