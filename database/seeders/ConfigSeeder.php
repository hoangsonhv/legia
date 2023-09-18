<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
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
                'label' => 'Tên công ty',
                'type'  => 'text',
                'key'   => 'name_company',
                'value' => ''
            ],
            [
                'label' => 'Địa chỉ công ty',
                'type'  => 'text',
                'key'   => 'address_company',
                'value' => ''
            ],
            [
                'label' => 'Số điện thoại',
                'type'  => 'text',
                'key'   => 'phone',
                'value' => ''
            ],
            [
                'label' => 'Lĩnh vực hoạt động',
                'type'  => 'text',
                'key'   => 'field_activity',
                'value' => ''
            ],
            [
                'label' => 'Mô tả',
                'type'  => 'textarea',
                'key'   => 'description',
                'value' => ''
            ],
            [
                'label' => 'Ảnh đại diện',
                'type'  => 'file',
                'key'   => 'avatar',
                'value' => ''
            ]
        ];
        Config::insert($data);
    }
}
