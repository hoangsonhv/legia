<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeHistory extends Model
{
    protected $fillable = [
        'form_type',
        'form_id',
        'action',
        'performed_by',
        'previous_status',
        'new_status',
        'change_details'
    ];

    // Thiết lập quan hệ đa hình
    public function formable()
    {
        return $this->morphTo();
    }

    public static function logChange($model, $action, $previousStatus, $newStatus, $details = null)
    {
        return self::create([
            'formable_type' => get_class($model),
            'formable_id' => $model->id,
            'action' => $action,
            'performed_by' => auth()->id(),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'change_details' => $details ? json_encode($details) : null,
        ]);
    }
}
