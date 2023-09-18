<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigApprovalCGSeeder extends Seeder
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
                'label' => 'Hạn mức xét duyệt của chào giá',
                'type'  => 'text',
                'key'   => 'limit_approval_cg',
                'value' => '100000000'
            ]
        ];
        Config::insert($data);
    }
}
