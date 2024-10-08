<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouhseHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group11 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'muc_ap_luc',
        'tieu_chuan',
        'kich_co',
        'kich_thuoc',
        'chuan_mat_bich',
        'chuan_gasket',
        'dvt',
        'lot_no',
        'ghi_chu',
        'sl_ton',
        'model_type'
    ];
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
            'vat_lieu'  => $this->vat_lieu,
            'do_day'    => $this->do_day,
            'muc_ap_luc'    => $this->muc_ap_luc,
            'tieu_chuan'    => $this->tieu_chuan,
            'kich_co'   => $this->kich_co,
            'kich_thuoc'    => $this->kich_thuoc,
            'chuan_mat_bich'    => $this->chuan_mat_bich,
            'chuan_gasket'  => $this->chuan_gasket,
            'dvt'   => $this->dvt,
        ];
    }
    public function getKichThuocAttribute() {
        return $this->kich_co;
    }
    public function setKichThuocAttribute() {
        return $this->kich_co;
    }
    public function getAcreageAttribute() {
        return null;
    }
    public function getDvTinhAttribute() {
        return $this->dvt;
    }
    public function getTonKhoAttribute() {
        return [
            'sl_ton' => $this->sl_ton,
        ];
    }

    public function setQuantity($qty, $accumulate = true) {
        if ($accumulate) {
            $this->sl_ton += $qty;
        }
        else
        {
            $this->sl_ton = $qty;
        }
    }

    public function getQuantityAttribute() {
        return $this->sl_ton;
    }
}
