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

class CoStepHistory extends Model
{
    const STEP_CREATE_RECEIPT_N1 = 'create_receipt_n1';
    const STEP_CREATE_RECEIPT_N2 = 'create_receipt_n2';
    const STEP_CREATE_RECEIPT_N3 = 'create_receipt_n3';
    const STEP_CREATE_RECEIPT_N4 = 'create_receipt_n4';
    const STEP_CREATE_REQUEST = 'create_request';
    const STEP_CREATE_PRICE_SURVEY = 'create_price_survey';
    const STEP_CREATE_PAYMENT_N1 = 'create_payment_n1';
    const STEP_CREATE_PAYMENT_N2 = 'create_payment_n2';
    const STEP_CREATE_PAYMENT_N3 = 'create_payment_n3';
    const STEP_CREATE_PAYMENT_N4 = 'create_payment_n4';
    const STEP_CREATE_WAREHOUSE_RECEIPT = 'create_warehouse_receipt';
    const STEP_CREATE_WAREHOUSE_EXPORT = 'create_warehouse_export';
    const STEP_CREATE_MANUFACTURE = 'create_manufacture';
    const STEP_CREATE_DELIVERY = 'create_delivery';
    const STEP_CREATE_WAREHOUSE_EXPORT_SELL = 'create_warehouse_export_sell';

    const STEP_WAITING_APPROVE_CO = 'waiting_approve_co';
    const STEP_WAITING_APPROVE_RECEIPT_N1 = 'waiting_approve_receipt_n1';
    const STEP_WAITING_APPROVE_RECEIPT_N2 = 'waiting_approve_receipt_n2';
    const STEP_WAITING_APPROVE_RECEIPT_N3 = 'waiting_approve_receipt_n3';
    const STEP_WAITING_APPROVE_RECEIPT_N4 = 'waiting_approve_receipt_n4';
    const STEP_WAITING_APPROVE_REQUEST = 'waiting_approve_request';
    const STEP_WAITING_APPROVE_PRICE_SURVEY = 'waiting_approve_price_survey';
    const STEP_WAITING_APPROVE_PAYMENT_N1 = 'waiting_approve_payment_n1';
    const STEP_WAITING_APPROVE_PAYMENT_N2 = 'waiting_approve_payment_n2';
    const STEP_WAITING_APPROVE_PAYMENT_N3 = 'waiting_approve_payment_n3';
    const STEP_WAITING_APPROVE_PAYMENT_N4 = 'waiting_approve_payment_n4';
    const STEP_WAITING_APPROVE_MANUFACTURE = 'waiting_approve_manufacture';
    const STEP_WAITING_APPROVE_QC = 'waiting_approve_qc';
    const STEP_WAITING_HANDLE_QC = 'waiting_handle_qc';
    const STEP_WAITING_APPROVE_DELIVERY = 'waiting_approve_delivery';

    const STEP_CHECKWAREHOUSE = 'check_warehouse';

    const ACTION_APPROVE = 'approve';
    const ACTION_CREATE = 'create';
    const ACTION_SELECT = 'select';
    const ACTION_APPROVE_PRICE_SURVEY = 'approve_price_survey';
    const ACTION_CREATE_PRICE_SURVEY = 'create_price_survey';

    protected $fillable = [
        'co_id',
        'step',
        'status',
        'object_id',
        'object_type'
    ];

    public function co()
    {
        return $this->belongsTo(Co::class, 'co_id');
    }
}
