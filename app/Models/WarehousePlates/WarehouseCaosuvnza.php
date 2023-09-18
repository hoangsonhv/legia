<?php

namespace App\Models\WarehousePlates;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehouseCaosuvnza extends Model
{
    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'hinh_dang',
        'dia_w_w1',
        'l_l1',
        'w2',
        'l2',
        'sl_tam',
        'sl_m2',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_tam',
        'ton_sl_m2'
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
