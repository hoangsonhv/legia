<?php

namespace App\Http\Middleware;

use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        // Log
        $prefixExl = [
            'admin/logadmin'
        ];
        if (!in_array($request->route()->getPrefix(), $prefixExl)) {
            AdminHelper::saveLogAdmin(json_encode($request->all()));
        }
        // Permission
        if(!$request->ajax()){
            $nameExl = [
                'admin.dashboard.index',
                'admin.administrator.meEdit',
                'admin.administrator.meUpdate',
            ];
            if (!in_array($request->route()->getName(), $nameExl)) {
                if (!PermissionHelper::hasPermission($request->route()->getName())) {
                    abort(403, 'Lỗi: Bạn không có quyền truy cập.');
                }
            }
        }
        return $next($request);
    }
}
