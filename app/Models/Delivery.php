<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'co_id',
        'shipping_unit',
        'delivery_date',
        'shipping_method',
        'shipping_fee',
        'status_customer_received',
        'admin_id',
        'core_customer_id',
        'recipient_name',
        'recipient_phone',
        'received_date_expected'
    ];

    public function co() {
        return $this->belongsTo(Co::class);
    }

    public function core_customer() {
        return $this->belongsTo(CoreCustomer::class, 'core_customer_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
