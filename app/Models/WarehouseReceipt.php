<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseReceipt extends Model
{
    protected $fillable = [
        'code',
        'delivery_name',
        'note',
        'warehouse_at',
        'address',
        'created_by',
        'amount_paid',
        'amount_owed',
        'total',
        'vat',
        'total_vat',
        'total_payment',
        'document',
        'co_id',
        'request_id'
    ];

    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($wr) {
            ChangeHistory::logChange($wr, 'created', null, 'created');
        });

        // Khi cập nhật
        static::updated(function ($wr) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($wr->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $wr->getOriginal('status'),
                    'new' => $wr->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $wr,
                    'updated', // Hành động cập nhật
                    $wr->getOriginal('status'), // Trạng thái trước
                    $wr->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }

    public function products() {
        return $this->hasMany(WarehouseReceiptProduct::class, 'warehouse_receipt_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function co() {
        return $this->belongsTo(Co::class, 'co_id');
    }
}
