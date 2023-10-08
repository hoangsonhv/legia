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
use Exception;
use Illuminate\Database\Eloquent\Model;

class WarehouhseHelper
{
    const BIA_CAOSU_CAOSUVNZA_TAMKIMLOAI_CREAMIC_GRAPHITE_PFTE_TAMNHUA   = 'GROUP_1';
    const FILLTER_GLANDPACKING_HOOP                                      = 'GROUP_2';
    const DAYCAOSU_SILICON_ONGGLASSEXPORT_DAYCREAMIC_PTFECAYONG_PTFETAPE = 'GROUP_3';
    const VANDTINH_INNER_OUTER                                           = 'GROUP_4';
    const NDLOAIKHAC_NKLOAIKHAC_ORING_RTJ                                = 'GROUP_5';
    const PHU_TUNG_DUNG_CU                                               = 'GROUP_6';
    const THANH_PHAM_SWG                                                 = 'GROUP_7';
    const GLAND_PACKING_LATTY                                            = 'GROUP_8';
    const CCDC                                                           = 'GROUP_9';
    const PTFE_ENVELOP                                                   = 'GROUP_10';
    const NHUAKYTHUAT_CAYONG                                             = 'GROUP_11';
    const KHO_THANH_PHAM_PHI_KIM_LOAI                                    = 'GROUP_12';

    const SHAPE_CICLE = '◯';
    const SHAPE_SQUARE = '☐';
    const SHAPE_POLYGON = '☐☐';

    public static function getModel(string $type, array $attributes = []): Model
    {
        switch ($type) {
            case WarehouhseHelper::BIA_CAOSU_CAOSUVNZA_TAMKIMLOAI_CREAMIC_GRAPHITE_PFTE_TAMNHUA:
                return new Group1($attributes);
            case WarehouhseHelper::FILLTER_GLANDPACKING_HOOP:
                return new Group2($attributes);
            case WarehouhseHelper::DAYCAOSU_SILICON_ONGGLASSEXPORT_DAYCREAMIC_PTFECAYONG_PTFETAPE:
                return new Group3($attributes);
            case WarehouhseHelper::VANDTINH_INNER_OUTER:
                return new Group4($attributes);
            case WarehouhseHelper::NDLOAIKHAC_NKLOAIKHAC_ORING_RTJ:
                return new Group5($attributes);
            case WarehouhseHelper::PHU_TUNG_DUNG_CU:
                return new Group6($attributes);
            case WarehouhseHelper::THANH_PHAM_SWG:
                return new Group7($attributes);
            case WarehouhseHelper::GLAND_PACKING_LATTY:
                return new Group8($attributes);
            case WarehouhseHelper::CCDC:
                return new Group9($attributes);
            case WarehouhseHelper::PTFE_ENVELOP:
                return new Group10($attributes);
            case WarehouhseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI:
                return new Group11();
            default:
                throw new Exception('Not Found Model');
        }
    }

}
