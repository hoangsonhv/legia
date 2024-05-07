<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\CoTmp;
use App\Models\Coable;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OfferPrice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Request;
use App\Models\WarehousePlates\WarehouseBia;
use App\Models\WarehousePlates\WarehouseCaosu;
use App\Models\WarehousePlates\WarehouseCaosuvnza;
use App\Models\WarehousePlates\WarehouseCeramic;
use App\Models\WarehousePlates\WarehouseGraphite;
use App\Models\WarehousePlates\WarehousePtfe;
use App\Models\WarehousePlates\WarehouseTamkimloai;
use App\Models\WarehousePlates\WarehouseTamnhua;
use App\Models\WarehouseSpws\WarehouseFiller;
use App\Models\WarehouseSpws\WarehouseGlandpackinglatty;
use App\Models\WarehouseSpws\WarehouseHoop;
use App\Models\WarehouseSpws\WarehouseOring;
use App\Models\WarehouseSpws\WarehousePtfeenvelope;
use App\Models\WarehouseSpws\WarehousePtfetape;
use App\Models\WarehouseSpws\WarehouseRtj;
use App\Models\WarehouseSpws\WarehouseThanhphamswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhinnerswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhouterswg;
use Illuminate\Database\Eloquent\Model;

class Co extends Model
{

    //default enough_material = 0;
    const ENOUGH_MATERIAL = 1; //đủ nvl
    const LACK_MATERIAL = 2;  // kh đủ nvl

    protected $table = 'co';

    protected $fillable = [
        'code',
        'description',
        'status',
        'so_bao_gia',
        'ngay_bao_gia',
        'sales',
        'thoi_han_bao_gia',
        'tong_gia',
        'vat',
        'dong_goi_va_van_chuyen',
        'noi_giao_hang',
        'xuat_xu',
        'thoi_gian_giao_hang',
        'thanh_toan',
        'confirm_done',
        'co_tmp_id',
        'enough_money',
        'start_timeline',
        'admin_id',
        'contract_document',
        'invoice_document',
        'core_customer_id',
        'enough_material',
        'note',
        'po_document'
    ];

    protected $casts = [
        'thanh_toan' => 'array'
    ];

    // public function coes() {
    //     return $this->hasMany(Coable::class);
    // }

    public function getRawCodeAttribute() {
        return str_replace('CO', '', $this->code);
    }

    public function payment() {
        return $this->morphedByMany(Payment::class, 'coables');
    }

    public function request() {
        return $this->morphedByMany(Request::class, 'coables');
    }

    public function receipt() {
        return $this->morphedByMany(Receipt::class, 'coables');
    }

    public function customer() {
        return $this->hasOne(Customer::class);
    }

    public function warehouses() {
        return $this->hasMany(OfferPrice::class);
    }

    public function co_tmp() {
        return $this->belongsTo(CoTmp::class);
    }

    public function delivery() {
        return $this->hasOne(Delivery::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function core_customer()
    {
        return $this->belongsTo(CoreCustomer::class, 'core_customer_id', 'id');
    }

    public function manufacture() {
        return $this->hasOne(Manufacture::class, 'co_id', 'id');
    }

    public function currentStep() {
        return $this->hasOne(CoStepHistory::class, 'co_id')->latest();
    }
    
    public function stepHistories()
    {
        return $this->hasMany('App\Models\CoStepHistory', 'co_id', 'id');
    }

    public function warehouseExports() 
    {
        return $this->hasMany(WarehouseExport::class, 'co_id', 'id');
    }
}
