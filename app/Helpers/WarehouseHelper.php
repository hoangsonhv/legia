<?php

namespace App\Helpers;

use App\Models\Document;
use App\Models\Manufacture;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WarehouseHelper
{
  
    # SHAPE
    const SHAPE_CICLE = 'RO1';
    const SHAPE_SQUARE = 'RE1';
    const SHAPE_POLYGON = 'RE2';

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
    const KHO_THANH_PHAM_KIM_LOAI = 30;

    public const PRODUCT_WAREHOUSES = [
        Manufacture::MATERIAL_TYPE_NON_METAL => self::KHO_THANH_PHAM_PHI_KIM_LOAI,
        Manufacture::MATERIAL_TYPE_METAL => self::THANH_PHAM_SWG
    ];

    #End Group 12
    public static function getModel(string $type, array $attributes = []): Model
    {
        // type of model
        $ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30];
        if(!in_array($type,$ids))
        {
            throw new NotFoundHttpException('Not Found Type');
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
            case WarehouseHelper::DAY_CREAMIC:
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

            case WarehouseHelper::PTFE_CAYONG:
                return new Group10($attributes);

            case WarehouseHelper::ONG_GLASS_EXPOXY:
            case WarehouseHelper::NHU_KY_THUAT_CAY_ONG:
                return new Group10($attributes);

            case WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI:
            case WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI:    
                return new Group11($attributes);

            case WarehouseHelper::CCDC:
                return new Group12($attributes);

            default:
                throw new NotFoundHttpException('Not Found Model');
        }
    }

    public static function warehouseEditPath($modelType) {
        $paths = [
            WarehouseHelper::BIA => '/warehouse-plate/edit/bia/',
            WarehouseHelper::CAO_SU => '/warehouse-plate/edit/caosu/',
            WarehouseHelper::CAO_SU_VN_ZA => '/warehouse-plate/edit/caosuvnza/',
            WarehouseHelper::TAM_KIM_LOAI => '/warehouse-plate/edit/tamkimloai/',
            WarehouseHelper::CREAMIC => '/warehouse-plate/edit/ceramic/',
            WarehouseHelper::GRAPHITE => '/warehouse-plate/edit/graphite/',
            WarehouseHelper::PTFE => '/warehouse-plate/edit/ptfe/',
            WarehouseHelper::TAM_NHUA => '/warehouse-plate/edit/tamnhua/',
            WarehouseHelper::FILLER => '/warehouse-spw/edit/filler/',
            WarehouseHelper::GLAND_PACKING => '/warehouse-remain/edit/glandpacking',
            WarehouseHelper::HOOP => '/warehouse-spw/edit/hoop/',
            WarehouseHelper::DAY_CAO_SU_VA_SILICON => '/warehouse-remain/edit/daycaosusilicone',
            WarehouseHelper::ONG_GLASS_EXPOXY => '/warehouse-remain/edit/ongglassepoxy',
            WarehouseHelper::DAY_CREAMIC => '/warehouse-remain/edit/dayceramic',
            WarehouseHelper::PTFE_CAYONG => '/warehouse-remain/edit/ptfecayong',
            WarehouseHelper::PTFE_TAPE => '/warehouse-spw/edit/ptfetape/',
            WarehouseHelper::VANH_TINH_INNER_SWG => '/warehouse-spw/edit/vanhtinhinnerswg/',
            WarehouseHelper::VANH_TINH_OUTER_SWG => '/warehouse-spw/edit/vanhtinhouterswg/',
            WarehouseHelper::ND_LOAI_KHAC => '/warehouse-remain/edit/ndloaikhac',
            WarehouseHelper::NK_LOAI_KHAC => '/warehouse-remain/edit/nkloaikhac',
            WarehouseHelper::ORING => '/warehouse-spw/edit/oring/',
            WarehouseHelper::RTJ => '/warehouse-spw/edit/rtj/',
            WarehouseHelper::PHU_TUNG_DUNG_CU => '/warehouse-remain/edit/phutungdungcu',
            WarehouseHelper::THANH_PHAM_SWG => '/warehouse-spw/edit/thanhphamswg/',
            WarehouseHelper::GLAND_PACKING_LATTY => '/warehouse-spw/edit/glandpackinglatty/',
            WarehouseHelper::CCDC => '/warehouse-remain/edit/ccdc',
            WarehouseHelper::PTFE_ENVELOP => '/warehouse-spw/edit/ptfeenvelope/',
            WarehouseHelper::NHU_KY_THUAT_CAY_ONG => '/warehouse-remain/edit/nhuakythuatcayong',
            WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI => '/warehouse-remain/edit/tpphikimloai',
            WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI => '/warehouse-remain/edit/tpkimloai',
        ];

        return $paths[$modelType];
    }

    public static function warehouseCreatePath($modelType) {
        $paths = [
            WarehouseHelper::BIA => '/warehouse-plate/create/bia/',
            WarehouseHelper::CAO_SU => '/warehouse-plate/create/caosu/',
            WarehouseHelper::CAO_SU_VN_ZA => '/warehouse-plate/create/caosuvnza/',
            WarehouseHelper::TAM_KIM_LOAI => '/warehouse-plate/create/tamkimloai/',
            WarehouseHelper::CREAMIC => '/warehouse-plate/create/ceramic/',
            WarehouseHelper::GRAPHITE => '/warehouse-plate/create/graphite/',
            WarehouseHelper::PTFE => '/warehouse-plate/create/ptfe/',
            WarehouseHelper::TAM_NHUA => '/warehouse-plate/create/tamnhua/',
            WarehouseHelper::FILLER => '/warehouse-spw/create/filler/',
            WarehouseHelper::GLAND_PACKING => '/warehouse-remain/create/glandpacking',
            WarehouseHelper::HOOP => '/warehouse-spw/create/hoop/',
            WarehouseHelper::DAY_CAO_SU_VA_SILICON => '/warehouse-remain/create/daycaosusilicone',
            WarehouseHelper::ONG_GLASS_EXPOXY => '/warehouse-remain/create/ongglassepoxy',
            WarehouseHelper::DAY_CREAMIC => '/warehouse-remain/create/dayceramic',
            WarehouseHelper::PTFE_CAYONG => '/warehouse-remain/create/ptfecayong',
            WarehouseHelper::PTFE_TAPE => '/warehouse-spw/create/ptfetape/',
            WarehouseHelper::VANH_TINH_INNER_SWG => '/warehouse-spw/create/vanhtinhinnerswg/',
            WarehouseHelper::VANH_TINH_OUTER_SWG => '/warehouse-spw/create/vanhtinhouterswg/',
            WarehouseHelper::ND_LOAI_KHAC => '/warehouse-remain/create/ndloaikhac',
            WarehouseHelper::NK_LOAI_KHAC => '/warehouse-remain/create/nkloaikhac',
            WarehouseHelper::ORING => '/warehouse-spw/create/oring/',
            WarehouseHelper::RTJ => '/warehouse-spw/create/rtj/',
            WarehouseHelper::PHU_TUNG_DUNG_CU => '/warehouse-remain/create/phutungdungcu',
            WarehouseHelper::THANH_PHAM_SWG => '/warehouse-spw/create/thanhphamswg/',
            WarehouseHelper::GLAND_PACKING_LATTY => '/warehouse-spw/create/glandpackinglatty/',
            WarehouseHelper::CCDC => '/warehouse-remain/create/ccdc',
            WarehouseHelper::PTFE_ENVELOP => '/warehouse-spw/create/ptfeenvelope/',
            WarehouseHelper::NHU_KY_THUAT_CAY_ONG => '/warehouse-remain/create/nhuakythuatcayong',
            WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI => '/warehouse-remain/create/tpphikimloai',
            WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI => '/warehouse-remain/create/tpkimloai',
        ];

        return $paths[$modelType];
    }

    public static function groupTonKhoKey($group_id) {
        $keys = [
            WarehouseHelper::BIA => 'ton_sl_tam',
            WarehouseHelper::CAO_SU => 'ton_sl_tam',
            WarehouseHelper::CAO_SU_VN_ZA => 'ton_sl_tam',
            WarehouseHelper::TAM_KIM_LOAI => 'ton_sl_tam',
            WarehouseHelper::CREAMIC => 'ton_sl_tam',
            WarehouseHelper::GRAPHITE => 'ton_sl_tam',
            WarehouseHelper::PTFE => 'ton_sl_tam',
            WarehouseHelper::TAM_NHUA => 'ton_sl_tam',
            WarehouseHelper::FILLER => 'ton_sl_cuon',
            WarehouseHelper::GLAND_PACKING => 'ton_sl_cuon',
            WarehouseHelper::HOOP => 'ton_sl_cuon',
            WarehouseHelper::DAY_CAO_SU_VA_SILICON => 'ton_sl_cuon',
            WarehouseHelper::ONG_GLASS_EXPOXY => 'ton_sl_cay',
            WarehouseHelper::DAY_CREAMIC => 'ton_sl_cuon',
            WarehouseHelper::PTFE_CAYONG => 'ton_sl_cay',
            WarehouseHelper::PTFE_TAPE => 'ton_sl_cuon',
            WarehouseHelper::VANH_TINH_INNER_SWG => 'ton_sl_cai',
            WarehouseHelper::VANH_TINH_OUTER_SWG => 'ton_sl_cai',
            WarehouseHelper::ND_LOAI_KHAC => 'ton_sl_cai',
            WarehouseHelper::NK_LOAI_KHAC => 'ton_sl_cai',
            WarehouseHelper::ORING => 'ton_sl_cai',
            WarehouseHelper::RTJ => 'ton_sl_cai',
            WarehouseHelper::PHU_TUNG_DUNG_CU => 'ton_sl_cai',
            WarehouseHelper::THANH_PHAM_SWG => 'ton_sl_cai',
            WarehouseHelper::GLAND_PACKING_LATTY => 'ton_sl_cai',
            WarehouseHelper::CCDC => 'ton_sl_cai',
            WarehouseHelper::PTFE_ENVELOP => 'ton_sl_cai',
            WarehouseHelper::NHU_KY_THUAT_CAY_ONG => 'ton_sl_cay',
            WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI => 'sl_ton',
            WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI => 'sl_ton',
        ];

        return $keys[$group_id];
    }

    public static function translateAtt($key)
    {
        $attributes = [
            'vat_lieu' => 'Vật liệu',
            'do_day' => 'Độ dày',
            'hinh_dang' => 'Hình dạng',
            'dia_w_w1' => 'Dia_W_W1',
            'l_l1' => 'L_L1',
            'w2' => 'W2',
            'l2' => 'L2',
            'size' => 'Size',
            'trong_luong_cuon' => 'Trọng lượng cuộn',
            'm_cuon' => 'm/cuộn',
            'sl_cuon' => 'Số lượng cuộn',
            'sl_kg' => 'Số lượng Kg',
            'ton_sl_cuon' => 'Tồn (cuộn)',
            'ton_sl_kg' => 'Tồn (kg)',
            'sl_m' => 'Số lượng m',
            'ton_sl_m' => 'Tồn (m)',
            'd1' => 'D1',
            'd2' => 'D2',
            'd3' => 'D3',
            'd4' => 'D4',
            'sl_cai' => 'Số lượng cái',
            'ton_sl_cai' => 'Tồn (cái)',
            'cho_may_moc_thiet_bi' => 'Cho máy móc, thiết bị',
            'so_hopdong_hoadon' => 'Số hơp đồng, hóa đơn',
            'inner' => 'Inner',
            'hoop' => 'Hoop',
            'filler' => 'Filler',
            'outer' => 'Outer',
            'thick' => 'Thick',
            'tieu_chuan' => 'Tiêu chuẩn',
            'kich_co' => 'Kích cỡ',
            'std' => 'Std',
            'od' => 'Od',
            'id' => 'Id',
            'm_cay' => 'm/cây',
            'sl_cay' => 'Số lượng cây',
            'ton_sl_cay' => 'Tồn (cây)',
            'muc_ap_luc' => 'Mức áp lực',
            'tieu_chuan' => 'Tiêu chuẩn',
            'kich_co' => 'Kích cỡ',
            'kich_thuoc' => 'Kích thước',
            'chuan_mat_bich' => 'Chuẩn mặt bích',
            'chuan_gasket' => 'Chuẩn gasket',
            'dvt' => 'Đơn vị tính',
            'sl_ton' => 'Tồn (cái)',
            'bo_phan' => 'Bộ phận',
            'sl' => 'Tồn (cái)',
            'ton_sl_tam' => 'Tồn (tấm)',
            'ton_sl_m2' => 'Tồn (m2)',
            'lot_no' => 'Lot .no',
            'ghi_chu' => 'Ghi chú',
            'date' => 'Date',
        ];

        return $attributes[$key];
    }

    public static function nonZeroWarehouseMerchandiseConditions() {
        return $conditions = [
            ['ton_sl_tam', '>', 0],
            ['ton_sl_cuon', '>', 0],
            ['ton_sl_cai', '>', 0],
            ['ton_sl_cay', '>', 0],
            ['sl_ton', '>', 0],
        ];
    }
}
