<?php

namespace App\Models;

use App\Models\Co;
use Illuminate\Database\Eloquent\Model;

class ManufactureDetail extends Model
{
    const MATERIAL_TYPE_METAL = 1;
    const MATERIAL_TYPE_NON_METAL = 0;

    protected $fillable = [
        'manufacture_id',
        'offer_price_id',
        'reality_quantity',
        'material_type',
        'need_quantity'
    ];

    public function offerPrice()
    {
        return $this->belongsTo(OfferPrice::class, 'offer_price_id', 'id');
    }
}
