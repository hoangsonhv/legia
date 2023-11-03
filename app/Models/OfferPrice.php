<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class OfferPrice extends Model
{
    const MATERIAL_TYPE_METAL = 1;
    const MATERIAL_TYPE_NON_METAL = 0;

    protected $fillable = [
        'co_id',
        'code',
        'loai_vat_lieu',
        'do_day',
        'tieu_chuan',
        'kich_co',
        'kich_thuoc',
        'chuan_bich',
        'chuan_gasket',
        'dv_tinh',
        'so_luong',
        'don_gia',
        'material_type',
        'manufacture_type',
        'warehouse_group_id',
        'merchandise_id',
    ];

    public function co() {
        return $this->belongsTo(Co::class);
    }
}
