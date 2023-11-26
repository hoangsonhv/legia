<?php

namespace App\Models;

use App\Helpers\AdminHelper;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;

class RequestMaterial extends Model
{
    protected $fillable = [
        'request_id',
        'merchandise_id',
        'code',
        'mo_ta',
        'kich_thuoc',
        'quy_cach',
        'dv_tinh',
        'dinh_luong',
        'thoi_gian_can',
        'ghi_chu',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    public function getDateAttribute($value) {
        return AdminHelper::convertDate($value);
    }

    public function request() {
        return $this->belongsTo(Request::class);
    }

    public function price_survey()
    {
        return $this->hasMany(PriceSurvey::class, 'material_id');
    }
}
