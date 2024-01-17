<?php

namespace App\Models;

use App\Models\BankHistoryTransaction;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    const TYPE_ATM = 0;
    const TYPE_CASH = 1;

    protected $fillable = [
        'name_bank', 'account_name', 'account_number', 'account_balance', 'opening_balance', 'type', 'admin_id'
    ];

    public function bankLoans() {
        return $this->hasMany(BankLoan::class);
    }
    public function history() {
    	return $this->hasMany(BankHistoryTransaction::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
    public function totalLoanDetails()
    {
        return $this->hasManyThrough(
            BankLoanDetail::class,
            BankLoan::class,
            'bank_id', // Khóa ngoại của BankLoan
            'bank_loan_id' // Khóa ngoại của BankLoanDetail
        );
    }

    public function getTotalDebitAmountAttribute()
    {
        return $this->totalLoanDetails()->sum('debit_amount');
    }

    public function getTotalProfitAmountAttribute()
    {
        return $this->totalLoanDetails()->sum('profit_amount');
    }

    public function getTotalAmountAttribute()
    {
        return $this->totalLoanDetails()->sum('total_amount');
    }
}
