<?php

namespace App\Imports\Plates;

use App\Helpers\AdminHelper;
use App\Models\WarehousePlates\WarehouseBia;
use App\Models\WarehousePlates\WarehouseCaosu;
use App\Models\WarehousePlates\WarehouseCaosuvnza;
use App\Models\WarehousePlates\WarehouseCeramic;
use App\Models\WarehousePlates\WarehouseGraphite;
use App\Models\WarehousePlates\WarehousePtfe;
use App\Models\WarehousePlates\WarehouseTamkimloai;
use App\Models\WarehousePlates\WarehouseTamnhua;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class WarehousePlatesImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts, WithValidation, WithCalculatedFormulas
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

        $data = [
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
        ];

        switch ($this->model) {
            case 'bia':
                return new WarehouseBia($data);
            case 'caosuvnza':
                return new WarehouseCaosuvnza($data);
            case 'caosu':
                return new WarehouseCaosu($data);
            case 'ceramic':
                return new WarehouseCeramic($data);
            case 'graphite':
                return new WarehouseGraphite($data);
            case 'ptfe':
                return new WarehousePtfe($data);
            case 'tamkimloai':
                return new WarehouseTamkimloai($data);
            case 'tamnhua':
                return new WarehouseTamnhua($data);
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
