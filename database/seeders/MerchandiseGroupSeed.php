<?php

namespace Database\Seeders;

use App\Models\MerchandiseGroup;
use Illuminate\Database\Seeder;

class MerchandiseGroupSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MerchandiseGroup::insert([
            ['name' => "Non metallic gasket-Standard", 'code' => "SG_STD", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Non metallic gasket-Non Standard", 'code' => "SG_NSTD", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Non metallic gasket sheet-full", 'code' => "SG_sheet", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Spiral wound gasket- Standard", 'code' => "SWG_STD", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Spiral wound gasket- Non Standard - Circle", 'code' => "SWG_NSTD", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Spiral wound gasket- Non Standard - Elip", 'code' => "SWG_NSTD", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Ring type joint gasket", 'code' => "RTJ", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Others packing", 'code' => "O-PK", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Latty packing", 'code' => "L-PK", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Plastic", 'code' => "Pls", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "PTFE Rod", 'code' => "PTFE_R", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "PTFE Tube", 'code' => "PTFE_T", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "PTFE Hose", 'code' => "PTFE_H", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Ceramic tape, rope, packing", 'code' => "Cer-PK", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Expanded PTFE", 'code' => "Exp-PTFE", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Rubber,silicone -cord ,strip", 'code' => "Rub strip", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "O-ring", 'code' => "O-ring", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::COMMERCE],
            ['name' => "Graphite nén", 'code' => "Gra nen", 'factory_type' => MerchandiseGroup::NON_METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Camprofile", 'code' => "Kam", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
            ['name' => "Double Jacketed", 'code' => "DBJ", 'factory_type' => MerchandiseGroup::METAL, 'operation_type' => MerchandiseGroup::MANUFACTURE],
        ]);
    }
}
