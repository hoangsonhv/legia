<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExportSell extends Model
{
    protected $table = 'warehouse_export_sell';

    protected $fillable = [
        'code',
        'core_customer_id',
        'buyer_name',
        'buyer_address',
        'buyer_phone',
        'buyer_tax_code',
        'note',
        'created_by',
        'currency',
        'amount_paid',
        'amount_owed',
        'total',
        'vat',
        'total_vat',
        'total_payment',
        'document',
        'confirm_enough',
        'co_id'
    ];


    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($wes) {
            ChangeHistory::logChange($wes, 'created', null, $wes->status);
        });

        // Khi cập nhật
        static::updated(function ($wes) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($wes->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $wes->getOriginal('status'),
                    'new' => $wes->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $wes,
                    'updated', // Hành động cập nhật
                    $wes->getOriginal('status'), // Trạng thái trước
                    $wes->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }

    const CURRENCY_VND = 1;

    public function products() {
        return $this->hasMany(WarehouseExportSellProduct::class, 'warehouse_export_sell_id', 'id');
    }

    public function core_customer() {
        return $this->belongsTo(CoreCustomer::class, 'core_customer_id', 'id');
    }

    public function co() {
        return $this->belongsTo(Co::class, 'co_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
