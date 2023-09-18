<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankLoanDetail extends Model
{
    protected $fillable = [
        'bank_loan_id',
        'debit_amount',
        'profit_amount',
        'total_amount',
        'note',
        'admin_id'
    ];

    public function bankLoan() {
        return $this->belongsTo(BankLoan::class, 'bank_loan_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
