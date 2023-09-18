<?php

namespace App\Models;

use App\Models\Request;
use Illuminate\Database\Eloquent\Model;

class SurveyPrice extends Model
{
    protected $fillable = [
        'request_id',
        'is_accept',
        'accompanying_document',
        'note',
        'core_price_survey_id'
    ];

    public function request() {
        return $this->belongsTo(Request::class);
    }

    public function corePriceSurvey() {
        return $this->belongsTo(PriceSurvey::class, 'core_price_survey_id', 'id');
    }
}
