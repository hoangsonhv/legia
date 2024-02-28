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
