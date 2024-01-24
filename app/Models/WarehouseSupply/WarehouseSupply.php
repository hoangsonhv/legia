<?php

namespace App\Models\WarehouseSupply;

use App\Helpers\AdminHelper;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class WarehouseSupply extends Model
{
    protected $fillable = [
        'code',
        'mo_ta',
        'dvt',
        'sl',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
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
