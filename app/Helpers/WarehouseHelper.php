<?php

namespace App\Helpers;

use App\Models\Document;
use App\Models\Warehouse\Group10;
use App\Models\Warehouse\Group11;
use App\Models\Warehouse\Group2;
use App\Models\Warehouse\Group3;
use App\Models\Warehouse\Group4;
use App\Models\Warehouse\Group5;
use App\Models\Warehouse\Group6;
use App\Models\Warehouse\Group7;
use App\Models\Warehouse\Group8;
use App\Models\Warehouse\Group9;
use App\Models\Warehouse\Group1;
use App\Models\Warehouse\Group12;
use App\Models\Warehouse\Group13;
use Exception;
use Illuminate\Database\Eloquent\Model;

class WarehouseHelper
{
  
    # SHAPE
    const SHAPE_CICLE = '◯';
    const SHAPE_SQUARE = '☐';
    const SHAPE_POLYGON = '☐☐';

    # name of warehouse

    const BIA = 1;
    const CAO_SU = 2;
    const CAO_SU_VN_ZA = 3;
    const TAM_KIM_LOAI = 4;
    const CREAMIC = 5;
    const GRAPHITE = 6;
    const PTFE = 7;
    const TAM_NHUA = 8;

    #End Group 1
    const FILLER = 9;
    const GLAND_PACKING = 10;
    const HOOP = 11;
    #End Group 2
    const DAY_CAO_SU_VA_SILICON = 12;
    const ONG_GLASS_EXPOXY = 13;
    const DAY_CREAMIC = 14;
    const PTFE_CAYONG = 15;
    const PTFE_TAPE = 16;
    #End Group 3
    const VANH_TINH_INNER_SWG = 17;
    const VANH_TINH_OUTER_SWG = 18;
    #End Group 4
    const ND_LOAI_KHAC = 19;
    const NK_LOAI_KHAC = 20;
    const ORING = 21;
    const RTJ = 22;
    #End Group 5
    const PHU_TUNG_DUNG_CU = 23;
    #End Group 6
    const THANH_PHAM_SWG = 24;
    #End Group 7
    const GLAND_PACKING_LATTY = 25;
    #End Group 8
    const CCDC = 26;
    #End Group 9
    const PTFE_ENVELOP = 27;
    #End Group 10
    const NHU_KY_THUAT_CAY_ONG = 28;
    #End Group 11
    const KHO_THANH_PHAM_PHI_KIM_LOAI = 29;

    
    #End Group 12
    public static function getModel(string $type, array $attributes = []): Model
    {
        // type of model
        $ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29];
        if(!in_array($type,$ids))
        {
            throw new Exception('Not Found Type');
        }
       
        switch ($type) {
            case WarehouseHelper::BIA:
            case WarehouseHelper::CAO_SU:
            case WarehouseHelper::CAO_SU_VN_ZA:
            case WarehouseHelper::TAM_KIM_LOAI:
            case WarehouseHelper::CREAMIC:
            case WarehouseHelper::GRAPHITE:
            case WarehouseHelper::PTFE:
            case WarehouseHelper::TAM_NHUA:
                return new Group1($attributes);
               
            case WarehouseHelper::FILLER:
            case WarehouseHelper::GLAND_PACKING:
            case WarehouseHelper::HOOP:
                return new Group2($attributes);
                
            case WarehouseHelper::DAY_CAO_SU_VA_SILICON:
            case WarehouseHelper::ONG_GLASS_EXPOXY:
            case WarehouseHelper::DAY_CREAMIC:
            case WarehouseHelper::PTFE_CAYONG:
            case WarehouseHelper::PTFE_TAPE:
                return new Group3($attributes);
              

            case WarehouseHelper::VANH_TINH_INNER_SWG:
                return new Group4($attributes);

            case WarehouseHelper::VANH_TINH_OUTER_SWG:
                return new Group13($attributes);

            case WarehouseHelper::ND_LOAI_KHAC:
            case WarehouseHelper::NK_LOAI_KHAC:
            case WarehouseHelper::ORING:
            case WarehouseHelper::RTJ:
                return new Group5($attributes);
               

            case WarehouseHelper::PHU_TUNG_DUNG_CU:
                return new Group6($attributes);
                

            case WarehouseHelper::THANH_PHAM_SWG:
                return new Group7($attributes);
                

            case WarehouseHelper::GLAND_PACKING_LATTY:
                return new Group8($attributes);
                

            case WarehouseHelper::PTFE_ENVELOP:
                return new Group9($attributes);
                

            case WarehouseHelper::NHU_KY_THUAT_CAY_ONG:
                return new Group10($attributes);
                

            case WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI:
                return new Group11($attributes);
                

            case WarehouseHelper::CCDC:
                return new Group12($attributes);
                
                
            default:
                throw new Exception('Not Found Model');
        }
        
  
    }
    
}
