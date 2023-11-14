<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group6 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'mo_ta',
        'cho_may_moc_thiet_bi',
        'sl_cai',
        'so_hopdong_hoadon',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];

    public function setDetailAttribute() {
        return [
            'mo_ta' => $this->mo_ta,
            'cho_may_moc_thiet_bi' => $this->cho_may_moc_thiet_bi,
            'lot_no' => $this->lot_no,
            'ghi_chu' => $this->ghi_chu,
            'date' => $this->date,
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

    public function setQuantity($qty) {
        $this->ton_sl_cai += $qty;
    }
}
