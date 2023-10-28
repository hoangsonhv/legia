<?php

namespace Database\Seeders;

use App\Models\MerchandiseGroupWareHouse;
use Illuminate\Database\Seeder;

class MerchandiseGroupWarehouseSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MerchandiseGroupWareHouse::insert([
            ['merchandise_group_id' => '1', 'warehouse_id' => '1'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '1'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '1'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '2'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '2'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '2'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '3'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '3'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '3'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '5'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '5'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '5'],
            ['merchandise_group_id' => '16', 'warehouse_id' => '12'],
            ['merchandise_group_id' => '14', 'warehouse_id' => '14'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '9'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '9'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '9'],
            ['merchandise_group_id' => '8', 'warehouse_id' => '10'],
            ['merchandise_group_id' => '9', 'warehouse_id' => '25'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '18', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '19', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '20', 'warehouse_id' => '6'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '11'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '11'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '11'],
            ['merchandise_group_id' => '10', 'warehouse_id' => '28'],
            ['merchandise_group_id' => '17', 'warehouse_id' => '21'],
            ['merchandise_group_id' => '15', 'warehouse_id' => '16'],
            ['merchandise_group_id' => '1', 'warehouse_id' => '7'],
            ['merchandise_group_id' => '2', 'warehouse_id' => '7'],
            ['merchandise_group_id' => '3', 'warehouse_id' => '7'],
            ['merchandise_group_id' => '11', 'warehouse_id' => '15'],
            ['merchandise_group_id' => '12', 'warehouse_id' => '15'],
            ['merchandise_group_id' => '13', 'warehouse_id' => '15'],
            ['merchandise_group_id' => '7', 'warehouse_id' => '22'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '19', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '20', 'warehouse_id' => '4'],
            ['merchandise_group_id' => '10', 'warehouse_id' => '8'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '24'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '24'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '24'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '17'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '17'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '17'],
            ['merchandise_group_id' => '4', 'warehouse_id' => '18'],
            ['merchandise_group_id' => '5', 'warehouse_id' => '18'],
            ['merchandise_group_id' => '6', 'warehouse_id' => '18'],
        ]);
    }
}
