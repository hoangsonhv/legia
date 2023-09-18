<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseExport extends Model
{
    protected $fillable = [
        'code',
        'recipient_name',
        'recipient_address',
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
        return $this->hasMany(WarehouseExportProduct::class, 'warehouse_export_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
