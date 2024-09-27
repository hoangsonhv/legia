<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group3 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'm_cuon',
        'sl_cuon',
        'sl_m',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'ton_sl_m',
        'model_type'
    ];
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'size' => $this->size,
            'm_cuon' => $this->m_cuon,
        ];
    }

    public function getAcreageAttribute() {
        return self::acreage($this->sl_cuon);
    }

    public function acreage($sl_cuon) {
        return $this->m_cuon * $sl_cuon;
    }
    public function getDvTinhAttribute() {
        return 'Cuộn';
    }
    public function getTonKhoAttribute() {
        return [
            'ton_sl_cuon' => $this->ton_sl_cuon,
            'ton_sl_m' => self::acreage($this->ton_sl_cuon),
        ];
    }
    public function setTonSlMAttribute() {
        return self::acreage($this->ton_sl_cuon);
    }
    public function getTonSlMAttribute() {
        return self::acreage($this->ton_sl_cuon);
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
