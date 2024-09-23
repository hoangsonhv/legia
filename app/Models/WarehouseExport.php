<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExport extends Model
{
    protected $fillable = [
        'code',
        'recipient_name',
        'recipient_address',
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
        static::created(function ($we) {
            ChangeHistory::logChange($we, 'created', null, $we->status);
        });

        // Khi cập nhật
        static::updated(function ($we) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($we->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $we->getOriginal('status'),
                    'new' => $we->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $we,
                    'updated', // Hành động cập nhật
                    $we->getOriginal('status'), // Trạng thái trước
                    $we->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }

    public function products() {
        return $this->hasMany(WarehouseExportProduct::class, 'warehouse_export_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function co() {
        return $this->belongsTo(Co::class);
    }
}
