<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'path',
        'admin_id',
        'note'
    ];

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
