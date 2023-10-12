<?php

namespace App\Models\WarehouseRemains;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehousePhutungdungcu extends Model
{
    protected $fillable = [
        'code',
        'mo_ta',
        'cho_may_moc_thiet_bi',
        'sl_cai',
        'so_hopdong_hoadon',
        'ghi_chu',
        'date',
        'ton_sl_cai',
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
