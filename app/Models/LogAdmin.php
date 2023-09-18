<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model {

	protected $fillable = [
        'admin_id', 'admin_name', 'ip', 'browser', 'link', 'content'
    ];
}
