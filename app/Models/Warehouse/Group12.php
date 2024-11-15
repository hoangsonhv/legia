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
    protected $appends = ['detail', 'dv_tinh'];

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
    public function getDvTinhAttribute() {
        return 'Cái';
    }
    public function getTonKhoAttribute() {
        return [
            'ton_sl_cai' => $this->ton_sl_cai,
        ];
    }

    public function setQuantity($qty, $accumulate = true) {
        if ($accumulate) {
            $this->ton_sl_cai += $qty;
        }
        else
        {
            $this->ton_sl_cai = $qty;
        }
    }
    public function getKichThuocAttribute() {
        return $this->size;
    }
    public function setKichThuocAttribute() {
        return $this->size;
    }
    public function getQuantityAttribute() {
        return $this->ton_sl_cai;
    }
}
