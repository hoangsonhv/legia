<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group13 extends BaseWarehouseCommon
{
    use HasFactory;

    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'd3',
        'd4',
        'sl_cai',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_cai',
        'model_type'
    ];

    public function getDetailAttribute() {
        return [
            'vat_lieu'    => $this->vat_lieu,
            'do_day'  => $this->do_day,
            'd3'   => $this->d3,
            'd4'   => $this->d4,
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
