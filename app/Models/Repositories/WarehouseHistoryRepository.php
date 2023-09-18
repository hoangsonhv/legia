<?php

namespace App\Models\Repositories;

use App\Models\WarehouseHistory;

class WarehouseHistoryRepository extends AdminRepository
{
    /**
     * @param $products
     * @param $type
     */
    public function updateWarehouseHistory($products, $type)
    {
        if(count($products)) {
            $dataInserts = [];
            foreach ($products as $product) {
                $classModel = app($product['table_name']);
                if($classModel) {
                    $model = $classModel->find($product['warehouse_id']);
                    if($model) {
                        if($model->ton_sl_cai) {
                            $model->ton_sl_cai = $this->calcQuanity($type, $model->ton_sl_cai, $product['quantity_reality']);
                        }
                        if($model->ton_sl_tam) {
                            $model->ton_sl_tam = $this->calcQuanity($type, $model->ton_sl_tam, $product['quantity_reality']);
                        }
                        if($model->ton_sl_m2) {
                            // Tính số lượng m2 theo hình dạng
                        }

                        $model->save();
                        array_push($dataInserts, [
                            'object_id' => $model->id,
                            'type' => $type,
                            'remaining' => $model->ton_sl_cai ? $model->ton_sl_cai : $model->ton_sl_tam,
                            'quantity' => $product['quantity_reality'],
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
            }
            WarehouseHistory::insert($dataInserts);
        }
    }

    protected function calcQuanity($type, $slTon, $quantity)
    {
        if($type == WarehouseHistory::TYPE_WAREHOUSE_RECEIPT) {
            return $slTon + $quantity;
        }
        if($type == WarehouseHistory::TYPE_WAREHOUSE_EXPORT) {
            return $slTon - $quantity < 0 ? 0 : $slTon - $quantity;
        }
    }
}
