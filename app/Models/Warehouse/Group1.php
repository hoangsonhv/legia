<?php

namespace App\Models\Warehouse;

use App\Helpers\WarehouseHelper;
use App\Models\Warehouse\BaseWarehouseCommon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Group1 extends BaseWarehouseCommon
{
    use HasFactory;
    protected $fillable = [
        'code',
        'vat_lieu',
        'do_day',
        'hinh_dang',
        'dia_w_w1',
        'l_l1',
        'w2',
        'l2',
        'sl_tam',
        'sl_m2',
        'lot_no',
        'ghi_chu',
        'date',
        'ton_sl_tam',
        'ton_sl_m2',
        'model_type'
    ];
    protected $appends = ['detail', 'dv_tinh'];

    public function getDetailAttribute() {
        return [
            'vat_lieu' => $this->vat_lieu,
            'do_day' => $this->do_day,
            'hinh_dang' => $this->hinh_dang,
            'dia_w_w1' => $this->dia_w_w1,
            'l_l1' => $this->l_l1,
            'w2' => $this->w2,
            'l2' => $this->l2,
        ];
    }

    public function getAcreageAttribute() {
        return $this->acreage($this->sl_tam);
    }
    public function acreage($sl_tam) {
        switch (strtoupper($this->hinh_dang) ) {
            case WarehouseHelper::SHAPE_CICLE :
                return ((pi()/4 * pow($this->dia_w_w1, 2))/pow(10,6)) * $sl_tam;
            case WarehouseHelper::SHAPE_SQUARE :
                return (($this->dia_w_w1 * $this->l_l1)/pow(10,6)) * $sl_tam;
            case WarehouseHelper::SHAPE_POLYGON :
                return (($this->dia_w_w1 * $this->l_l1 - (($this->dia_w_w1 - $this->w2) * ($this->l_l1 - $this->l2)))/pow(10,6)) * $sl_tam;
            default:
                return 0;
                //throw new NotFoundHttpException('Not found hinh_dang');
        }
    }

    public function getDvTinhAttribute() {
        return 'tấm';
    }
    public function getTonKhoAttribute() {
        return [
            'ton_sl_tam' => $this->ton_sl_tam,
            'ton_sl_m2' => $this->acreage($this->ton_sl_tam),
        ];
    }

    public function setTonSlM2Attribute() {
        return $this->acreage($this->ton_sl_tam);
    }
    public function getTonSlM2Attribute() {
        return $this->acreage($this->ton_sl_tam);
    }

    public function setQuantity($qty, $accumulate = true) {
        if ($accumulate) {
            $this->ton_sl_tam += $qty;
        }
        else
        {
            $this->ton_sl_tam = $qty;
        }
    }

    public function getQuantityAttribute() {
        return $this->ton_sl_tam;
    }
}
