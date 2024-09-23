<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\CoTmp;
use App\Models\Coable;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OfferPrice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;

class Co extends Model
{

    //default enough_material = 0;
    const ENOUGH_MATERIAL = 1; //đủ nvl
    const LACK_MATERIAL = 2;  // kh đủ nvl

    protected $table = 'co';

    protected $fillable = [
        'code',
        'description',
        'status',
        'so_bao_gia',
        'ngay_bao_gia',
        'sales',
        'thoi_han_bao_gia',
        'tong_gia',
        'vat',
        'dong_goi_va_van_chuyen',
        'is_enough_export_sell',
        'noi_giao_hang',
        'xuat_xu',
        'thoi_gian_giao_hang',
        'thanh_toan',
        'confirm_done',
        'co_tmp_id',
        'enough_money',
        'start_timeline',
        'admin_id',
        'contract_document',
        'invoice_document',
        'core_customer_id',
        'enough_material',
        'note',
        'po_document'
    ];

    protected $casts = [
        'thanh_toan' => 'array'
    ];

    // public function coes() {
    //     return $this->hasMany(Coable::class);
    // }
    // Lắng nghe sự kiện khi tạo và cập nhật phiếu
    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($co) {
            ChangeHistory::logChange($co, 'created', null, $co->status);
        });

        // Khi cập nhật
        static::updated(function ($co) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($co->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $co->getOriginal('status'),
                    'new' => $co->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $co,
                    'updated', // Hành động cập nhật
                    $co->getOriginal('status'), // Trạng thái trước
                    $co->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }
    
    public function getRawCodeAttribute() {
        return str_replace('CO', '', $this->code);
    }

    public function payment() {
        return $this->morphedByMany(Payment::class, 'coables');
    }

    public function request() {
        return $this->morphedByMany(Request::class, 'coables');
    }

    public function receipt() {
        return $this->morphedByMany(Receipt::class, 'coables');
    }

    public function customer() {
        return $this->hasOne(Customer::class);
    }

    public function warehouses() {
        return $this->hasMany(OfferPrice::class);
    }

    public function co_tmp() {
        return $this->belongsTo(CoTmp::class);
    }

    public function delivery() {
        return $this->hasOne(Delivery::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function core_customer()
    {
        return $this->belongsTo(CoreCustomer::class, 'core_customer_id', 'id');
    }

    public function manufacture() {
        return $this->hasOne(Manufacture::class, 'co_id', 'id');
    }

    public function currentStep() {
        return $this->hasOne(CoStepHistory::class, 'co_id')->latest();
    }
    
    public function stepHistories()
    {
        return $this->hasMany('App\Models\CoStepHistory', 'co_id', 'id');
    }

    public function warehouseExports() 
    {
        return $this->hasMany(WarehouseExport::class, 'co_id', 'id');
    }
    public function warehouseReceipts() 
    {
        return $this->hasMany(WarehouseReceipt::class, 'co_id', 'id');
    }
    public function warehouseExportSells() 
    {
        return $this->hasMany(WarehouseExportSell::class, 'co_id', 'id');
    }

    // Quan hệ đa hình với bảng change_histories
    public function changeHistories()
    {
        return $this->morphMany(ChangeHistory::class, 'formable');
    }
    
}
