<?php

namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;

class BankHistoryTransaction extends Model
{
    protected $fillable = [
        'bank_id', 'admin_id', 'type', 'transaction_amount', 'current_amount', 'note',
        'payment_id',
        'receipt_id',
        'current_opening_balance'
    ];

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function receipt() {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }
}
