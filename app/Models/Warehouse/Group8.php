<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group8 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'sl_cuon',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'model_type'
    ];
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'size' => $this->size,
        ];
    }
       
    public function getAcreageAttribute() {
        return null;
    }
    public function getDvTinhAttribute() {
        return 'Cuộn';
    }
    public function getTonKhoAttribute() {
        return [
            'ton_sl_cuon' => $this->ton_sl_cuon,
        ];
    }
    public function getKichThuocAttribute() {
        return $this->size;
    }
    public function setKichThuocAttribute() {
        return $this->size;
    }
    public function setQuantity($qty, $accumulate = true) {
        if ($accumulate) {
            $this->ton_sl_cuon += $qty;
        }
        else
        {
            $this->ton_sl_cuon = $qty;
        }
    }

    public function getQuantityAttribute() {
        return $this->ton_sl_cuon;
    }
}
