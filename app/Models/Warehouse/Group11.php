<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group11 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'muc_ap_luc',
        'kich_co',
        'kich_thuoc',
        'chuan_mat_bich',
        'chuan_gasket',
        'dvt',
        'lot_no',
        'ghi_chu',
        'sl_ton',
    ];

    protected $attributes = [
        'model_type' => WarehouhseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI,
    ];
}
