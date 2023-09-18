<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class PermissionHelper
{
    public static function hasPermission($route)
    {
        if (!config('permission.role_permission')) {
            return true;
        }

        $permissions = self::getPermissions();
        if (is_array($route)) {
            foreach ($route as $vRoute) {
                if (in_array($vRoute, $permissions)) {
                    return true;
                }
            }
        } else {
            if (in_array($route, $permissions)) {
                return true;
            }
        }
        return false;
    }

    private static function getPermissions()
    {
        $permissions = [];
        $admin       = Session::get('login');
        $roles       = $admin->roles()->get();
        if ($roles) {
            foreach ($roles as $kRole => $vRole) {
                if ($vRole->permission) {
                    $permissions = array_merge($permissions, $vRole->permission);
                }
            }
        }
        return $permissions;
    }
}
