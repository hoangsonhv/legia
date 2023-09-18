<?php

namespace App\Models\WarehouseSpws;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehouseGlandpackinglatty extends Model
{
    protected $table    = 'warehouse_glandpackinglattys';
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'sl_cuon',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
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
