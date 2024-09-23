<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'co_id',
        'co_code',
        'code',
        'category',
        'request_id',
        'note',
        'admin_id',
        'name_receiver',
        'accompanying_document',
        'money_total',
        'status',
        'payment_method',
        'bank_id',
        'step_id'
    ];

    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($payment) {
            ChangeHistory::logChange($payment, 'created', null,1);
        });

        // Khi cập nhật
        static::updated(function ($payment) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($payment->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $payment->getOriginal('status'),
                    'new' => $payment->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $payment,
                    'updated', // Hành động cập nhật
                    $payment->getOriginal('status'), // Trạng thái trước
                    $payment->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }

    public function co() {
        return $this->morphToMany(Co::class, 'coables');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
    public function Request() {
        return $this->belongsTo(Request::class);
    }
}
