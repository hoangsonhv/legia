<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group10 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'size',
        'm_cay',
        'sl_cay',
        'sl_m',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'ton_sl_cay',
        'ton_sl_m',
        'model_type'
    ];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'size' => $this->size,
            'm_cay' => $this->std,
            'lot_no' => $this->lot_no,
            'ghi_chu' => $this->ghi_chu,
            'date' => $this->date,
        ];
    }
       
    public function getAcreageAttribute() {
        return self::acreage($this->sl_cay);
    }

    public function acreage($sl_cay) {
        return $this->m_cay * $sl_cay;
    }

    public function getTonKhoAttribute() {
        return [
            'ton_sl_cay' => $this->ton_sl_cay,
            'ton_sl_m'  => self::acreage($this->ton_sl_cay),
        ];
    }

    public function setQuantity($qty) {
        $this->ton_sl_tam += $qty;
    }
}
