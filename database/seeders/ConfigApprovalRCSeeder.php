<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigApprovalRCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'label' => 'Phần trăm xét duyệt phiếu thu (%)',
                'type'  => 'number',
                'key'   => 'limit_approval_rc',
                'value' => '2'
            ]
        ];
        Config::insert($data);
    }
}
