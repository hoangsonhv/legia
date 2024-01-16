<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankLoan extends Model
{
    protected $fillable = [
        'bank_id',
        'code',
        'lead',
        'content',
        'date',
        'date_due',
        'date_pay',
        'amount_money',
        'profit_amount',
        'outstanding_balance',
        'note',
        'admin_id',
        'loan_type'
    ];

    public function bank() {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
