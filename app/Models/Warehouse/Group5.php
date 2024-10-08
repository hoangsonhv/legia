<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group5 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'size' => $this->size,
        ];
    }
    public function getKichThuocAttribute() {
        return "";
    }
    public function setKichThuocAttribute() {
        return "";
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

    public function getQuantityAttribute() {
        return $this->ton_sl_cai;
    }
}
