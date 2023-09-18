<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'ten',
        'dia_chi',
        'mst',
        'nguoi_nhan',
        'dien_thoai',
        'email',
    ];

    public function co() {
        return $this->belongsTo(Co::class);
    }
}
