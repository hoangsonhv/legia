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

    public function history() {
    	return $this->hasMany(BankHistoryTransaction::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
