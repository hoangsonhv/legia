<?php

namespace Database\Seeders;

use App\Helpers\AdminHelper;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name'     => 'Admin',
            'username' => 'admin',
            'password' => AdminHelper::hashPassword('admin', 'H80UKC89FVUGwlqQj77XJc6k3XYBqe'),
            'mail'     => 'admin@gmail.com',
            'status'   => 1,
        ];
        Admin::create($admin);
    }
}
