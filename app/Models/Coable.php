<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coable extends Model
{
    protected $table = 'coables';

    protected $fillable = [
        'co_id', 'coables_id', 'coables_type'
    ];

    public function coables() {
        return $this->morphTo();
    }
}
