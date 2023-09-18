<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'display_name', 'description', 'permission'
    ];

    protected $casts = [
	    'permission' => 'array',
	];

    public function admins() {
    	return $this->belongsToMany(Admin::class, 'role_admin');
    }
}
