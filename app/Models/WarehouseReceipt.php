<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseReceipt extends Model
{
    protected $fillable = [
        'code',
        'delivery_name',
        'note',
        'warehouse_at',
        'address',
        'created_by',
        'amount_paid',
        'amount_owed',
        'total',
        'vat',
        'total_vat',
        'total_payment',
        'document',
        'co_id'
    ];

    public function products() {
        return $this->hasMany(WarehouseReceiptProduct::class, 'warehouse_receipt_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
