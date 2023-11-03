<?php

namespace App\Imports\Warehouse;

use App\Helpers\AdminHelper;
use App\Helpers\WarehouseHelper;
use App\Models\WarehousePlates\WarehouseBia;
use App\Models\WarehousePlates\WarehouseCaosu;
use App\Models\WarehousePlates\WarehouseCaosuvnza;
use App\Models\WarehousePlates\WarehouseCeramic;
use App\Models\WarehousePlates\WarehouseGraphite;
use App\Models\WarehousePlates\WarehousePtfe;
use App\Models\WarehousePlates\WarehouseTamkimloai;
use App\Models\WarehousePlates\WarehouseTamnhua;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class WarehouseImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts, WithCalculatedFormulas
{
    private $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function startRow(): int
    {
        return 3;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if (empty($row[0])) {
            return null;
        }
        switch ($this->model) {
            case 'bia':
                return  WarehouseHelper::getModel(WarehouseHelper::BIA, $this->getDataGroup1($row, WarehouseHelper::BIA));
            case 'caosuvnza':
                return WarehouseHelper::getModel(WarehouseHelper::CAO_SU_VN_ZA, $this->getDataGroup1($row, WarehouseHelper::CAO_SU_VN_ZA));
            case 'caosu':
                return WarehouseHelper::getModel(WarehouseHelper::CAO_SU, $this->getDataGroup1($row, WarehouseHelper::CAO_SU));
            case 'ceramic':
                return WarehouseHelper::getModel(WarehouseHelper::CREAMIC, $this->getDataGroup1($row, WarehouseHelper::CREAMIC));
            case 'graphite':
                return WarehouseHelper::getModel(WarehouseHelper::GRAPHITE, $this->getDataGroup1($row, WarehouseHelper::GRAPHITE));
            case 'ptfe':
                return WarehouseHelper::getModel(WarehouseHelper::PTFE, $this->getDataGroup1($row, WarehouseHelper::PTFE));
            case 'tamkimloai':
                return WarehouseHelper::getModel(WarehouseHelper::TAM_KIM_LOAI, $this->getDataGroup1($row, WarehouseHelper::TAM_KIM_LOAI));
            case 'tamnhua':
                return WarehouseHelper::getModel(WarehouseHelper::TAM_NHUA, $this->getDataGroup1($row, WarehouseHelper::TAM_NHUA));
            case 'filler':
                return  WarehouseHelper::getModel(WarehouseHelper::FILLER, $this->getDataGroup2($row, WarehouseHelper::FILLER));
            case 'glandpackinglatty':
                return  WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING_LATTY, $this->getDataGroup8($row, WarehouseHelper::GLAND_PACKING_LATTY));
            case 'hoop':
                return  WarehouseHelper::getModel(WarehouseHelper::HOOP, $this->getDataGroup2($row, WarehouseHelper::HOOP));
            case 'oring':
                return  WarehouseHelper::getModel(WarehouseHelper::ORING, $this->getDataGroup5($row, WarehouseHelper::ORING));
            case 'ptfeenvelope':
                return  WarehouseHelper::getModel(WarehouseHelper::PTFE_ENVELOP, $this->getDataGroup10($row, WarehouseHelper::PTFE_ENVELOP));
            case 'ptfetape':
                return  WarehouseHelper::getModel(WarehouseHelper::PTFE_TAPE, $this->getDataGroup3($row, WarehouseHelper::PTFE_TAPE));
            case 'rtj':
                return  WarehouseHelper::getModel(WarehouseHelper::RTJ, $this->getDataGroup5($row, WarehouseHelper::RTJ));
            case 'thanhphamswg':
                return  WarehouseHelper::getModel(WarehouseHelper::THANH_PHAM_SWG, $this->getDataGroup7($row, WarehouseHelper::THANH_PHAM_SWG));
            case 'vanhtinhinnerswg':
                return  WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_INNER_SWG, $this->getDataGroup4($row, WarehouseHelper::VANH_TINH_INNER_SWG));
            case 'vanhtinhouterswg':
                return  WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_OUTER_SWG, $this->getDataGroup13($row, WarehouseHelper::VANH_TINH_OUTER_SWG));
            case 'ccdc':
                return  WarehouseHelper::getModel(WarehouseHelper::CCDC, $this->getDataGroup9($row, WarehouseHelper::CCDC));
            case 'daycaosusilicone':
                return  WarehouseHelper::getModel(WarehouseHelper::DAY_CAO_SU_VA_SILICON, $this->getDataGroup3($row, WarehouseHelper::DAY_CAO_SU_VA_SILICON));
            case 'dayceramic':
                return  WarehouseHelper::getModel(WarehouseHelper::DAY_CREAMIC, $this->getDataGroup3($row, WarehouseHelper::DAY_CREAMIC));
            case 'glandpacking':
                return  WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING, $this->getDataGroup2($row, WarehouseHelper::GLAND_PACKING));
            case 'nhuakythuatcayong':
                return  WarehouseHelper::getModel(WarehouseHelper::NHU_KY_THUAT_CAY_ONG, $this->getDataGroup11($row, WarehouseHelper::NHU_KY_THUAT_CAY_ONG));
            case 'ongglassepoxy':
                return  WarehouseHelper::getModel(WarehouseHelper::ONG_GLASS_EXPOXY, $this->getDataGroup11($row, WarehouseHelper::ONG_GLASS_EXPOXY));
            case 'phutungdungcu':
                return  WarehouseHelper::getModel(WarehouseHelper::PHU_TUNG_DUNG_CU, $this->getDataGroup6($row, WarehouseHelper::PHU_TUNG_DUNG_CU));
            case 'ptfecayong':
                return  WarehouseHelper::getModel(WarehouseHelper::PTFE_CAYONG, $this->getDataGroup11($row, WarehouseHelper::PTFE_CAYONG));
            case 'ndloaikhac':
                return  WarehouseHelper::getModel(WarehouseHelper::ND_LOAI_KHAC, $this->getDataGroup5($row, WarehouseHelper::ND_LOAI_KHAC));
            case 'nkloaikhac':
                return  WarehouseHelper::getModel(WarehouseHelper::NK_LOAI_KHAC, $this->getDataGroup5($row, WarehouseHelper::NK_LOAI_KHAC));
            case 'tpphikimloai':
                return  WarehouseHelper::getModel(WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI, $this->getDataGroup12($row, WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI));
        }
    }

    /**
     * @return array
     */
    // public function rules(): array
    // {
    //     // return [
    //     //     '0' => ['required'],
    //     //     '1' => ['required'],
    //     // ];
    // }

    /**
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'Vui lòng nhập Mã hàng hoá!',
            '1.required' => 'Vui lòng nhập Vật liệu!',
        ];
    }

    public function chunkSize(): int
    {
        return 300;
    }

    public function batchSize(): int
    {
        return 300;
    }

    private function transformDateTime(string $value, string $format = 'Y-m-d')
    {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format($format);
        } catch (\ErrorException $e) {
            return AdminHelper::convertDate($value, $format);
        }
    }

    private function getDataGroup1($row, $modelType)
    {
        $data =  [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'do_day'     => floatval($row[2]),
            'hinh_dang'  => $row[3],
            'dia_w_w1'   => floatval($row[4]),
            'l_l1'       => floatval($row[5]),
            'w2'         => floatval($row[6]),
            'l2'         => floatval($row[7]),
            'sl_tam'     => floatval($row[8]),
            'sl_m2'      => floatval($row[9]),
            'lot_no'     => $row[10],
            'ghi_chu'    => $row[11],
            'date'       => now(),
            'ton_sl_tam' => floatval($row[13]),
            'ton_sl_m2'  => floatval($row[14]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup2($row, $modelType)
    {
        $data =  [
            'code'                => $row[0],
            'vat_lieu'            => $row[1],
            'size'                => floatval($row[2]),
            'trong_luong_cuon' => floatval($row[3]),
            'm_cuon'              => floatval($row[4]),
            'sl_cuon'             => floatval($row[5]),
            'sl_kg'               => floatval($row[6]),
            'lot_no'              => $row[7],
            'ghi_chu'             => $row[8],
            'date'                => now(),
            'ton_sl_cuon'         => floatval($row[10]),
            'ton_sl_kg'           => floatval($row[11]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup3($row, $modelType)
    {
        $data = [
            'code'        => $row[0],
            'vat_lieu'    => $row[1],
            'size'        => $row[2],
            'm_cuon'      => floatval($row[3]),
            'sl_cuon'     => floatval($row[4]),
            'sl_m'        => floatval($row[5]),
            'lot_no'      => $row[6],
            'ghi_chu'     => $row[7],
            'date'        => now(),
            'ton_sl_cuon' => floatval($row[9]),
            'ton_sl_m'    => floatval($row[10]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup4($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'do_day'     => floatval($row[2]),
            'd1'         => floatval($row[3]),
            'd2'         => floatval($row[4]),
            'sl_cai'     => floatval($row[5]),
            'lot_no'     => $row[6],
            'ghi_chu'    => $row[7],
            'date'       => !empty($row[8]) ? $this->transformDateTime($row[8]) : null,
            'ton_sl_cai' => floatval($row[9]),
            'model_type' => $modelType
        ];

        return $data;
    }
    private function getDataGroup13($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'do_day'     => floatval($row[2]),
            'd3'         => floatval($row[3]),
            'd4'         => floatval($row[4]),
            'sl_cai'     => floatval($row[5]),
            'lot_no'     => $row[6],
            'ghi_chu'    => $row[7],
            'date'       => !empty($row[8]) ? $this->transformDateTime($row[8]) : null,
            'ton_sl_cai' => floatval($row[9]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup5($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'size'       => $row[2],
            'sl_cai'     => floatval($row[3]),
            'lot_no'     => $row[4],
            'ghi_chu'    => $row[5],
            'date'       => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
            'ton_sl_cai' => floatval($row[7]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup6($row, $modelType)
    {
        $data = [
            'code'               => $row[0],
            'mo_ta'              => $row[1],
            'cho_may_moc_thiet_bi' => $row[2],
            'sl_cai'             => floatval($row[3]),
            'so_hopdong_hoadon'  => $row[4],
            'ghi_chu'            => $row[5],
            'date'               => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
            'ton_sl_cai'         => floatval($row[7]),
            'model_type' => $modelType
        ];

        return $data;
    }

    private function getDataGroup7($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'inner'      => $row[1],
            'hoop'       => $row[2],
            'filler'     => $row[3],
            'outer'      => $row[4],
            'thick'      => $row[5],
            'tieu_chuan' => $row[6],
            'kich_co'    => $row[7],
            'sl_cai'     => floatval($row[8]),
            'lot_no'     => $row[9],
            'ghi_chu'    => $row[10],
            'date'       => now(),
            'ton_sl_cai' => floatval($row[12]),
            'model_type' => $modelType
        ];
        return $data;
    }

    private function getDataGroup8($row, $modelType)
    {
        $data = [
            'code'        => $row[0],
            'vat_lieu'    => $row[1],
            'size'        => $row[2],
            'sl_cuon'     => floatval($row[3]),
            'lot_no'      => $row[4],
            'ghi_chu'     => $row[5],
            'date'        => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
            'ton_sl_cuon' => floatval($row[7]),
            'model_type' => $modelType
        ];
        return $data;
    }

    private function getDataGroup9($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'mo_ta'      => $row[1],
            'bo_phan'    => $row[2],
            'dvt'        => $row[3],
            'sl'         => floatval($row[4]),
            'lot_no'     => $row[5],
            'ghi_chu'    => $row[6],
            'date'       => now(),
            'ton_sl_cai' => floatval($row[8]),
            'model_type' => $modelType
        ];
        return $data;
    }
    private function getDataGroup10($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'do_day'     => floatval($row[2]),
            'std'        => $row[3],
            'size'       => $row[4],
            'od'         => floatval($row[5]),
            'attr_id'    => floatval($row[6]),
            'sl_cai'     => floatval($row[7]),
            'lot_no'     => $row[8],
            'ghi_chu'    => $row[9],
            'date'       => !empty($row[10]) ? $this->transformDateTime($row[10]) : null,
            'ton_sl_cai' => floatval($row[11]),
            'model_type' => $modelType
        ];
        return $data;
    }
    private function getDataGroup11($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'size'       => $row[2],
            'm_cay'      => floatval($row[3]),
            'sl_cay'     => floatval($row[4]),
            'sl_m'       => floatval($row[5]),
            'lot_no'     => $row[6],
            'ghi_chu'    => $row[7],
            'date'       => !empty($row[8]) ? $this->transformDateTime($row[8]) : null,
            'ton_sl_cay' => floatval($row[9]),
            'ton_sl_m'   => floatval($row[10]),
            'model_type' => $modelType
        ];
        return $data;
    }

    private function getDataGroup12($row, $modelType)
    {
        $data = [
            'code'       => $row[0],
            'vat_lieu'   => $row[1],
            'do_day'       => floatval($row[2]),
            'muc_ap_luc'      => $row[3],
            'kich_co'     => $row[4],
            'kich_thuoc'       => $row[5],
            'chuan_mat_bich'     => $row[6],
            'chuan_gasket'    => $row[7],
            'dvt'       => $row[8],
            'lot_no' => $row[9],
            'ghi_chu'   => $row[10],
            'sl_ton'   => $row[10],
            'model_type' => $modelType

        ];
        return $data;
    }
}
