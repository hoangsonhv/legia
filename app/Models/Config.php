<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

	protected $fillable = [
        'label', 'type', 'key', 'value'
    ];

    public $timestamps = false;
}
