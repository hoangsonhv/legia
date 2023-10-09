<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group7 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'inner',
        'hoop',
        'filler',
        'outer',
        'thick',
        'tieu_chuan',
        'kich_co',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];
    // protected $attributes = [
    //     'model_type' => WarehouhseHelper::THANH_PHAM_SWG,
    // ];
}
