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
