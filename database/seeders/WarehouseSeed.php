<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Warehouse::insert([
            ['name' => 'Kho bìa', 'model_type' => '1'],
            ['name' => 'Kho cao su', 'model_type' => '2'],
            ['name' => 'Kho cao su VN ZA', 'model_type' => '3'],
            ['name' => 'Kho tấm kim loại', 'model_type' => '4'],
            ['name' => 'Kho ceramic', 'model_type' => '5'],
            ['name' => 'Kho graphite', 'model_type' => '6'],
            ['name' => 'Kho PTFE', 'model_type' => '7'],
            ['name' => 'Kho tấm nhựa', 'model_type' => '8'],
            ['name' => 'Kho filler', 'model_type' => '9'],
            ['name' => 'Kho gland packing', 'model_type' => '10'],
            ['name' => 'Kho hoop', 'model_type' => '11'],
            ['name' => 'Kho dây cao su, silicon', 'model_type' => '12'],
            ['name' => 'Kho ống glass, epoxy', 'model_type' => '13'],
            ['name' => 'Kho dây ceramic', 'model_type' => '14'],
            ['name' => 'Kho PTFE - cây ống', 'model_type' => '15'],
            ['name' => 'Kho PTFE - tape', 'model_type' => '16'],
            ['name' => 'Kho vành tinh - inner-SWG', 'model_type' => '17'],
            ['name' => 'Kho vành tinh - outer-SWG', 'model_type' => '18'],
            ['name' => 'Kho ND loại khác', 'model_type' => '19'],
            ['name' => 'Kho NK loại khác', 'model_type' => '20'],
            ['name' => 'Kho O-ring', 'model_type' => '21'],
            ['name' => 'Kho RTJ', 'model_type' => '22'],
            ['name' => 'Kho phụ tùng dụng cụ', 'model_type' => '23'],
            ['name' => 'Kho thành phẩm SWG', 'model_type' => '24'],
            ['name' => 'Kho gland packing latty', 'model_type' => '25'],
            ['name' => 'Kho CCDC', 'model_type' => '26'],
            ['name' => 'Kho PTFE Envelop', 'model_type' => '27'],
            ['name' => 'Kho nhựa kỹ thuật - Cây ống', 'model_type' => '28'],
            ['name' => 'Kho thành phẩm phi kim loại', 'model_type' => '29'],
        ]);
    }
}
