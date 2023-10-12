<?php

namespace App\Imports\Remains;

use App\Helpers\AdminHelper;
use App\Models\WarehouseRemains\WarehouseCcdc;
use App\Models\WarehouseRemains\WarehouseDaycaosusilicone;
use App\Models\WarehouseRemains\WarehouseDayceramic;
use App\Models\WarehouseRemains\WarehouseGlandpacking;
use App\Models\WarehouseRemains\WarehouseNdloaikhac;
use App\Models\WarehouseRemains\WarehouseNhuakythuatcayong;
use App\Models\WarehouseRemains\WarehouseNkloaikhac;
use App\Models\WarehouseRemains\WarehouseOngglassepoxy;
use App\Models\WarehouseRemains\WarehousePhutungdungcu;
use App\Models\WarehouseRemains\WarehousePtfecayong;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehouseRemainsImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts, WithValidation, WithCalculatedFormulas
{
    private $model; 

    public function __construct($model)
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
            case 'ccdc':
                $data = [
                    'code'       => $row[0],
                    'mo_ta'      => $row[1],
                    'bo_phan'    => $row[2],
                    'dvt'        => $row[3],
                    'sl'         => floatval($row[4]),
                    'lot_no'     => $row[5],
                    'ghi_chu'    => $row[6],
                    'date'       => !empty($row[7]) ? $this->transformDateTime($row[7]) : null,
                    'ton_sl_cai' => floatval($row[8]),
                ];
                return new WarehouseCcdc($data);
            case 'daycaosusilicone':
                $data = [
                    'code'        => $row[0],
                    'vat_lieu'    => $row[1],
                    'size'        => $row[2],
                    'm_cuon'      => floatval($row[3]),
                    'sl_cuon'     => floatval($row[4]),
                    'sl_m'        => floatval($row[5]),
                    'lot_no'      => $row[6],
                    'ghi_chu'     => $row[7],
                    'date'        => !empty($row[8]) ? $this->transformDateTime($row[8]) : null,
                    'ton_sl_cuon' => floatval($row[9]),
                    'ton_sl_m'    => floatval($row[10]),
                ];
                return new WarehouseDaycaosusilicone($data);
            case 'dayceramic':
                $data = [
                    'code'        => $row[0],
                    'vat_lieu'    => $row[1],
                    'size'        => $row[2],
                    'm_cuon'      => floatval($row[3]),
                    'sl_cuon'     => floatval($row[4]),
                    'sl_m'        => floatval($row[5]),
                    'lot_no'      => $row[6],
                    'ghi_chu'     => $row[7],
                    'date'        => !empty($row[8]) ? $this->transformDateTime($row[8]) : null,
                    'ton_sl_cuon' => floatval($row[9]),
                    'ton_sl_m'    => floatval($row[10]),
                ];
                return new WarehouseDayceramic($data);
            case 'glandpacking':
                $data = [
                    'code'                => $row[0],
                    'vat_lieu'            => $row[1],
                    'size'                => floatval($row[2]),
                    'trong_luong_kg_cuon' => floatval($row[3]),
                    'm_cuon'              => floatval($row[4]),
                    'sl_cuon'             => floatval($row[5]),
                    'sl_kg'               => floatval($row[6]),
                    'lot_no'              => $row[7],
                    'ghi_chu'             => $row[8],
                    'date'                => !empty($row[9]) ? $this->transformDateTime($row[9]) : null,
                    'ton_sl_cuon'         => floatval($row[10]),
                    'ton_sl_kg'           => floatval($row[11]),
                ];
                return new WarehouseGlandpacking($data);
            case 'nhuakythuatcayong':
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
                ];
                return new WarehouseNhuakythuatcayong($data);
            case 'ongglassepoxy':
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
                ];
                return new WarehouseOngglassepoxy($data);
            case 'phutungdungcu':
                $data = [
                    'code'               => $row[0],
                    'mo_ta'              => $row[1],
                    'cho_may_moc_thiet_bi' => $row[2],
                    'sl_cai'             => floatval($row[3]),
                    'so_hopdong_hoadon'  => $row[4],
                    'ghi_chu'            => $row[5],
                    'date'               => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
                    'ton_sl_cai'         => floatval($row[7]),
                ];
                return new WarehousePhutungdungcu($data);
            case 'ptfecayong':
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
                ];
                return new WarehousePtfecayong($data);
            case 'ndloaikhac':
                $data = [
                    'code'       => $row[0],
                    'vat_lieu'   => $row[1],
                    'size'       => $row[2],
                    'sl_cai'     => floatval($row[3]),
                    'lot_no'     => $row[4],
                    'ghi_chu'    => $row[5],
                    'date'       => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
                    'ton_sl_cai' => floatval($row[7]),
                ];
                return new WarehouseNdloaikhac($data);
            case 'nkloaikhac':
                $data = [
                    'code'       => $row[0],
                    'vat_lieu'   => $row[1],
                    'size'       => $row[2],
                    'sl_cai'     => floatval($row[3]),
                    'lot_no'     => $row[4],
                    'ghi_chu'    => $row[5],
                    'date'       => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
                    'ton_sl_cai' => floatval($row[7]),
                ];
                return new WarehouseNkloaikhac($data);
        }
    }

    /**
    * @return array
    */
    public function rules(): array
    {
        $valid = [
            '0' => ['required'],
            '1' => ['required'],
        ];
        $exceptModel = ['ccdc', 'phutungdungcu'];
        if (in_array($this->model, $exceptModel)) {
            unset($valid['1']);
        }
        return $valid;
    }

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
}
