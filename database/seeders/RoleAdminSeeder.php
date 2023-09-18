<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = $permissionLead = [];
        foreach (config('permission.permissions') as $kPermission => $vPermission) {
            $routes      = array_keys($vPermission['routes']);
            $permissions = array_merge($permissions, $routes);
            if (!in_array($kPermission, ['role', 'config'])) {
                $permissionLead = array_merge($permissionLead, $routes);
            }
        }
        $roles = [
            [
                'display_name' => 'Giám đốc',
                'description'  => 'Người quản trị có tất cả quyền của hệ thống',
                'permission'   => $permissions
            ],
            [
                'display_name' => 'Trưởng phòng',
                'description'  => 'Quản lý các phòng ban',
                'permission'   => $permissionLead
            ],
            [
                'display_name' => 'Sale',
                'description'  => 'Phòng ban sale',
                'permission'   => [
                    'admin.co-tmp.index',
                    'admin.co-tmp.create',
                    'admin.co-tmp.store',
                    'admin.co-tmp.edit',
                    'admin.co-tmp.update',
                    'admin.co.index',
                    'admin.co.create',
                    'admin.co.store',
                    'admin.co.edit',
                    'admin.co.update',
                    'admin.co.list-info-co',
                    'admin.co.delivery.save',
                    'admin.request.index',
                    'admin.request.create',
                    'admin.request.store',
                    'admin.request.edit',
                    'admin.request.update',
                    'admin.request.update-survey-price',
                ]
            ],
            [
                'display_name' => 'Kho',
                'description'  => 'Phòng ban Kho',
                'permission'   => [
                    'admin.request.index',
                    'admin.request.create',
                    'admin.request.store',
                    'admin.request.edit',
                    'admin.request.update',
                    'admin.warehouse-plate.index',
                    'admin.warehouse-plate.create',
                    'admin.warehouse-plate.store',
                    'admin.warehouse-plate.edit',
                    'admin.warehouse-plate.update',
                    'admin.warehouse-plate.import',
                    'admin.warehouse-spw.index',
                    'admin.warehouse-spw.create',
                    'admin.warehouse-spw.store',
                    'admin.warehouse-spw.edit',
                    'admin.warehouse-spw.update',
                    'admin.warehouse-spw.import',
                    'admin.warehouse-remain.index',
                    'admin.warehouse-remain.create',
                    'admin.warehouse-remain.store',
                    'admin.warehouse-remain.edit',
                    'admin.warehouse-remain.update',
                    'admin.warehouse-remain.import',
                ]
            ],
            [
                'display_name' => 'Kế Toán',
                'description'  => 'Phòng ban Kế Toán',
                'permission'   => [
                    'admin.co.index',
                    'admin.co.create',
                    'admin.co.store',
                    'admin.co.edit',
                    'admin.co.update',
                    'admin.co.list-info-co',
                    'admin.co.delivery.save',
                    'admin.request.index',
                    'admin.request.create',
                    'admin.request.store',
                    'admin.request.edit',
                    'admin.request.update',
                    'admin.request.update-survey-price',
                    'admin.payment.index',
                    'admin.payment.create',
                    'admin.payment.store',
                    'admin.payment.edit',
                    'admin.payment.update',
                    'admin.receipt.index',
                    'admin.receipt.create',
                    'admin.receipt.store',
                    'admin.receipt.edit',
                    'admin.receipt.update',
                ]
            ],
            [
                'display_name' => 'Giao Nhận',
                'description'  => 'Phòng ban Giao Nhận',
                'permission'   => [
                    'admin.co.index',
                    'admin.co.edit',
                    'admin.co.update',
                    'admin.co.list-info-co',
                    'admin.co.delivery.save',
                    'admin.request.index',
                    'admin.request.create',
                    'admin.request.store',
                    'admin.request.edit',
                    'admin.request.update',
                ]
            ],
        ];
        foreach($roles as $role) {
            Role::create($role);
        }

        $roleAdmin = [
            'admin_id' => 1,
            'role_id'  => 1
        ];
        DB::table("role_admin")->insert($roleAdmin);
    }
}
