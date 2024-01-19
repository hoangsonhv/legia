<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RequestStepHistory extends Model
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
    const STEP_WAITING_APPROVE_DELIVERY = 'waiting_approve_delivery';

    const STEP_CHECKWAREHOUSE = 'check_warehouse';

    const ACTION_APPROVE = 'approve';
    const ACTION_CREATE = 'create';
    const ACTION_SELECT = 'select';
    const ACTION_APPROVE_PRICE_SURVEY = 'approve_price_survey';
    const ACTION_CREATE_PRICE_SURVEY = 'create_price_survey';

    protected $fillable = [
        'request_id',
        'step',
        'status',
        'object_id',
        'object_type'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
