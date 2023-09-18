<?php 
namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model {

    const POSITION_STAFF = 0;
    const POSITION_LEADER = 1;

    const ARR_POSITION = [
        self::POSITION_STAFF => 'Nhân viên',
        self::POSITION_LEADER => 'Trưởng phòng'
    ];

	protected $fillable = [
        'name', 'username', 'password', 'mail', 'image', 'lasttime', 'status', 'position_id'
    ];

    public function roles(){
    	return $this->belongsToMany(Role::class, 'role_admin');
    }
}
