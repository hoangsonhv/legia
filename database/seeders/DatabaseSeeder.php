<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!Admin::where(['username' => 'admin'])->count()) {
            $this->call(AdminSeeder::class);
            $this->call(RoleAdminSeeder::class);
        }
        $this->call(ConfigSeeder::class);
        $this->call(ConfigApprovalCGSeeder::class);
    }
}
