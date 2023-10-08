<?php

namespace App\Imports\Warehouse;

use App\Helpers\AdminHelper;
use App\Helpers\WarehouhseHelper;
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

class WarehouseImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts, WithValidation, WithCalculatedFormulas
{
    private $type; 

    public function __construct(string $type)
    {
        $this->type = $type;     
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
        
        switch ($this->type) {
            
            case WarehouhseHelper::BIA_CAOSU_CAOSUVNZA_TAMKIMLOAI_CREAMIC_GRAPHITE_PFTE_TAMNHUA:
                return  WarehouhseHelper::getModel(WarehouhseHelper::BIA_CAOSU_CAOSUVNZA_TAMKIMLOAI_CREAMIC_GRAPHITE_PFTE_TAMNHUA,$this->getDataGroup1($row));
            
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

    private function getDataGroup1($row){
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
        ];

        return $data;
    }
}
