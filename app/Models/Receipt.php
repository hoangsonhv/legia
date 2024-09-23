<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Co;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{

    const PAYMENT_METHOD_ATM = 2;

    protected $fillable = [
        'co_id',
        'co_code',
        'code',
        'payment_id',
        'payment_method',
        'note',
        'admin_id',
        'name_receiver',
        'accompanying_document',
        'money_total',
        'actual_money',
        'debt_money',
        'status',
        'bank_id',
        'step_id'
    ];

    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($receipt) {
            ChangeHistory::logChange($receipt, 'created', null, 1);
        });

        // Khi cập nhật
        static::updated(function ($receipt) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($receipt->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $receipt->getOriginal('status'),
                    'new' => $receipt->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $receipt,
                    'updated', // Hành động cập nhật
                    $receipt->getOriginal('status'), // Trạng thái trước
                    $receipt->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }
    
    public function co() {
        return $this->morphToMany(Co::class, 'coables');
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
