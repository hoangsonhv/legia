<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPriceSurvey extends Model
{
    protected $fillable = [
        'request_id',
        'price_survey',
        'is_accept',
        'note',
    ];
}
