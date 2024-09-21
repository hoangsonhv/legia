<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    const WAITING = 0;
    const PROCESSING = 1;
    const IS_COMPLETED = 2;

    const MATERIAL_TYPE_METAL = 1;
    const MATERIAL_TYPE_NON_METAL = 0;

    protected $fillable = [
        'co_id',
        'note',
        'is_completed',
        'qc_check',
        'material_type',
        'manufacture_type',
        'admin_id'
    ];
    protected static function booted()
    {
        // // Khi tạo mới
        // static::created(function ($wr) {
        //     ChangeHistory::logChange($wr, 'created', null, 'created');
        // });

        // Khi cập nhật
        static::updated(function ($wr) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($wr->isDirty('is_completed')) {
                $changes['is_completed'] = [
                    'previous' => $wr->getOriginal('is_completed'),
                    'new' => $wr->is_completed,
                ];
            }
            // Kiểm tra nếu status thay đổi
            if ($wr->isDirty('qc_check')) {
                $changes['qc_check'] = [
                    'previous' => $wr->getOriginal('qc_check'),
                    'new' => $wr->qc_check,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $wr,
                    'updated', // Hành động cập nhật
                    $changes['previous'], // Trạng thái trước
                    $changes['new'], // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }
    public function co()
    {
        return $this->belongsTo(Co::class, 'co_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ManufactureDetail::class, 'manufacture_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
