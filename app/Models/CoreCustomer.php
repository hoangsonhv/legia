<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class CoreCustomer extends Model
{
    const TYPE_CUSTOMER = 0;
    const TYPE_SUPPLIER = 1;

    const ARR_TYPE = [
        self::TYPE_CUSTOMER => 'Khách hàng',
        self::TYPE_SUPPLIER => 'Nhà cung cấp'
    ];

    protected $table = 'core_customer';
    protected $fillable = [
        'code',
        'name',
        'tax_code',
        'address',
        'phone',
        'email',
        'recipient',
        'admin_id',
        'type'
    ];

    public function co() {
        return $this->belongsTo(Co::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
