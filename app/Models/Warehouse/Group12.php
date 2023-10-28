<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group12 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'mo_ta',
        'bo_phan',
        'dvt',
        'sl',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];

    public function getDetailAttribute() {
        return [
            'mo_ta'    => $this->mo_ta,
            'bo_phan'  => $this->bo_phan,
            'dvt'   => $this->dvt,
        ];
    }
       
    public function getAcreageAttribute() {
        return null;
    }

    public function getTonKhoAttribute() {
        return [
            'ton_sl_cai' => $this->ton_sl_cai,
        ];
    }
}
