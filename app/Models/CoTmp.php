<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Co;
use App\Models\Customer;
use App\Models\OfferPriceTmp;
use Illuminate\Database\Eloquent\Model;

class CoTmp extends Model
{
    protected $fillable = [
        'code',
        'admin_id',
        'description',
        'status',
        'so_bao_gia',
        'ngay_bao_gia',
        'sales',
        'thoi_han_bao_gia',
        'tong_gia',
        'vat',
        'dong_goi_va_van_chuyen',
        'noi_giao_hang',
        'xuat_xu',
        'thoi_gian_giao_hang',
        'thanh_toan',
        'nguoi_nhan',
        'core_customer_id',
        'co_id',
        'note',
        'co_not_approved_id'
    ];

    protected $casts = [
        'thanh_toan' => 'array'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function core_customer()
    {
        return $this->belongsTo(CoreCustomer::class, 'core_customer_id', 'id');
    }

    public function warehouses() {
        return $this->hasMany(OfferPriceTmp::class);
    }

    public function coes() {
        return $this->hasMany(Co::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function co_not_approved() {
        return $this->belongsTo(Co::class, 'co_not_approved_id', 'id');
    }
}
