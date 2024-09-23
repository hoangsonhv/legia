<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ChangeHistory extends Model
{
    protected $fillable = [
        'formable_type', 
        'formable_id',
        'action',
        'performed_by',
        'previous_status',
        'new_status',
        'change_details',
        'performed_at',
    ];

    // Thiết lập quan hệ đa hình
    public function formable()
    {
        return $this->morphTo();
    }

    // Liên kết với bảng admin (thay đổi nếu cần)
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }

    // Hàm log thay đổi
    public static function logChange($model, $action, $previousStatus, $newStatus, $details = null)
    {
        $user = Session::get('login');
        $performedBy = $user ? $user->id : null;

        return self::create([
            'formable_type' => get_class($model), // Tên class của model liên kết
            'formable_id' => $model->id,          // ID của model liên kết
            'action' => $action,                  // Hành động (created, approved, etc.)
            'performed_by' => $performedBy,       // ID của user thực hiện hành động
            'previous_status' => $previousStatus, // Trạng thái trước
            'new_status' => $newStatus,           // Trạng thái sau
            'change_details' => $details ? (is_array($details) ? json_encode($details) : $details) : null,
            'performed_at' => now(),              // Thời điểm thực hiện
        ]);
    }
}
