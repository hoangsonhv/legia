<?php

namespace App\Models;

use App\Models\CoTmp;
use Illuminate\Database\Eloquent\Model;

class OfferPriceTmp extends Model
{
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
        'type',
        'vat',
        'vat_money',
        'manufacture_type',
        'warehouse_group_id',
        'material_type'
    ];

    public function co() {
        return $this->belongsTo(CoTmp::class);
    }
}
