<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class MerchandiseCode extends Model
{
    protected $table = "merchandise_codes";
    protected $fillable = [
        'code',
        'prefix_code',
        'infix_code',
        'summary',
    ];
}
