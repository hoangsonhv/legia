<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSurvey extends Model
{
    protected $table = 'price_survey';
    protected $fillable = [
        'request_id',
        'type',
        'supplier',
        'product_group',
        'request_person',
        'date_request',
        'question_date',
        'result_date',
        'deadline',
        'status',
        'price',
        'vendor_code',
        'number_date_wait_pay',
        'note',
        'co_id',
        'admin_id',
        'material_id'
    ];

    const TYPE_NVLNK = 1;
    const TYPE_NVLND = 2;
    const TYPE_OTHER= 3;
    const ARR_TYPE = [
        self::TYPE_NVLNK => 'NVLNK',
        self::TYPE_NVLND => 'NVLND',
        self::TYPE_OTHER => 'Khác'
    ];

    const TYPE_BUY = 1;
    const TYPE_FAIL = 2;
    const ARR_STATUS = [
        0 => 'None',
        self::TYPE_FAIL => 'Fail',
        self::TYPE_BUY => 'Buy',
    ];

    public function surveyPrices()
    {
        return $this->hasMany(SurveyPrice::class, 'core_price_survey_id', 'id');
    }
    public function co()
    {
        return $this->belongsTo(Co::class, 'co_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
