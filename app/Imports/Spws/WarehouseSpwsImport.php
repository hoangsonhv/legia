<?php

namespace App\Imports\Spws;

use App\Helpers\AdminHelper;
use App\Models\WarehouseSpws\WarehouseFiller;
use App\Models\WarehouseSpws\WarehouseGlandpackinglatty;
use App\Models\WarehouseSpws\WarehouseHoop;
use App\Models\WarehouseSpws\WarehouseOring;
use App\Models\WarehouseSpws\WarehousePtfeenvelope;
use App\Models\WarehouseSpws\WarehousePtfetape;
use App\Models\WarehouseSpws\WarehouseRtj;
use App\Models\WarehouseSpws\WarehouseThanhphamswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhinnerswg;
use App\Models\WarehouseSpws\WarehouseVanhtinhouterswg;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarehouseSpwsImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts, WithValidation, WithCalculatedFormulas
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
            case 'filler':
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
                return new WarehouseFiller($data);
            case 'glandpackinglatty':
                $data = [
                    'code'        => $row[0],
                    'vat_lieu'    => $row[1],
                    'size'        => $row[2],
                    'sl_cuon'     => floatval($row[3]),
                    'lot_no'      => $row[4],
                    'ghi_chu'     => $row[5],
                    'date'        => !empty($row[6]) ? $this->transformDateTime($row[6]) : null,
                    'ton_sl_cuon' => floatval($row[7]),
                ];
                return new WarehouseGlandpackinglatty($data);
            case 'hoop':
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
                return new WarehouseHoop($data);
            case 'oring':
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
                return new WarehouseOring($data);
            case 'ptfeenvelope':
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
                ];
                return new WarehousePtfeenvelope($data);
            case 'ptfetape':
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
                return new WarehousePtfetape($data);
            case 'rtj':
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
                return new WarehouseRtj($data);
            case 'thanhphamswg':
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
                    'date'       => !empty($row[11]) ? $this->transformDateTime($row[11]) : null,
                    'ton_sl_cai' => floatval($row[12]),
                ];
                return new WarehouseThanhphamswg($data);
            case 'vanhtinhinnerswg':
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
                ];
                return new WarehouseVanhtinhinnerswg($data);
            case 'vanhtinhouterswg':
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
                ];
                return new WarehouseVanhtinhouterswg($data);
        }
    }

    /**
    * @return array
    */
    public function rules(): array
    {
        return [
            '0' => ['required'],
            '1' => ['required'],
        ];
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
