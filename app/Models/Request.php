<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Co;
use App\Models\RequestMaterial;
use App\Models\SurveyPrice;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'co_id',
        'co_code',
        'code',
        'category',
        'admin_id',
        'note',
        'accompanying_document',
        'accompanying_document_survey_price',
        'status',
        'thanh_toan',
        'money_total'
    ];

    protected $casts = [
        'thanh_toan' => 'array'
    ];

    protected static function booted()
    {
        // Khi tạo mới
        static::created(function ($requestModel) {
            ChangeHistory::logChange($requestModel, 'created', null, 'created');
        });

        // Khi cập nhật
        static::updated(function ($requestModel) {
            $changes = [];

            // Kiểm tra nếu status thay đổi
            if ($requestModel->isDirty('status')) {
                $changes['status'] = [
                    'previous' => $requestModel->getOriginal('status'),
                    'new' => $requestModel->status,
                ];
            }
            // Nếu có thay đổi, thì ghi lại lịch sử
            if (!empty($changes)) {
                ChangeHistory::logChange(
                    $requestModel,
                    'updated', // Hành động cập nhật
                    $requestModel->getOriginal('status'), // Trạng thái trước
                    $requestModel->status, // Trạng thái sau
                    $changes // Chi tiết các thay đổi
                );
            }
        });
    }

    public function co() {
        return $this->morphToMany(Co::class, 'coables');
    }

    public function material() {
        return $this->hasMany(RequestMaterial::class);
    }

    public function surveyPrices() {
        return $this->hasMany(SurveyPrice::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function currentStep() {
        return $this->hasOne(RequestStepHistory::class, 'request_id')->latest();
    }
}
