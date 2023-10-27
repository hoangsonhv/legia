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

    public function setDetailAttribute() {
        return [
            'inner'  => $this->inner,
            'hoop'  => $this->hoop,
            'filler'  => $this->filler,
            'outer'  => $this->outer,
            'thick'  => $this->thick,
            'tieu_chuan'  => $this->tieu_chuan,
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
