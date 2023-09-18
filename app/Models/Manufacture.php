<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    const WAITING = 0;
    const PROCESSING = 1;
    const IS_COMPLETED = 2;

    const MATERIAL_TYPE_METAL = 1;
    const MATERIAL_TYPE_NON_METAL = 0;

    protected $fillable = [
        'co_id',
        'note',
        'is_completed',
        'material_type',
        'admin_id'
    ];

    public function co()
    {
        return $this->belongsTo(Co::class, 'co_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ManufactureDetail::class, 'manufacture_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
