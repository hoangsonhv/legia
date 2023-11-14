<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group2 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'trong_luong_cuon',
        'm_cuon',
        'sl_cuon',
        'sl_kg',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cuon',
        'ton_sl_kg',
        'model_type'
    ];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'size' => $this->size,
            'trong_luong_cuon' => $this->trong_luong_cuon,
            'm_cuon' => $this->m_cuon,
            'lot_no' => $this->lot_no,
            'ghi_chu' => $this->ghi_chu,
            'date' => $this->date,
        ];
    }
   
    public function getAcreageAttribute() {
        return self::acreage($this->sl_cuon);
    }

    public function acreage($sl_cuon) {
        return $this->trong_luong_cuon * $sl_cuon;
    }

    
    public function getTonKhoAttribute() {
        return [
            'ton_sl_cuon' => $this->ton_sl_cuon,
            'ton_sl_kg' => self::acreage($this->ton_sl_cuon),
        ];
    }

    public function setQuantity($qty) {
        $this->ton_sl_cuon += $qty;
    }
}
