<?php

namespace App\Models\Repositories;

use Illuminate\Support\Carbon;

class BaseRepository
{
    protected $model;

    protected $prefix_id = '';

    public function getIdCurrent() 
    {
        // $data = $this->model->orderBy('id', 'desc')->limit(1)->first();
        $data = \DB::select("SHOW TABLE STATUS LIKE '".$this->model->getTable()."'");
        if ($data) {
            // $increment = $data->id + 1;
            $increment = $data[0]->Auto_increment;
            return $this->prefix_id . sprintf("%010d", $increment);
        }
        return $this->prefix_id . sprintf("%010d", 1);
    }

    public function setQueryCondition(&$query, array $arrCondition)
    {
        if(isset($arrCondition['from_date']) && $arrCondition['from_date']) {
            $query = $query->whereDate('created_at', '>=', $arrCondition['from_date']);
        }
        if(isset($arrCondition['to_date']) && $arrCondition['to_date']) {
            $query = $query->whereDate('created_at', '<=', $arrCondition['to_date']);
        }
        if(isset($arrCondition['month']) && $arrCondition['month']) {
            $startDay = [
                date("Y"),
                $arrCondition['month'],
                '00'
            ];
            $endDay = [
                date("Y"),
                $arrCondition['month'],
                Carbon::now()->lastOfMonth()->format("d")
            ];
            $query = $query->whereDate('created_at', '>=', implode('-', $startDay))
                ->where('created_at', '<=', implode('-', $endDay));
        }
        if(isset($arrCondition['year']) && $arrCondition['year']) {
            $query = $query->whereYear('created_at', $arrCondition['year']);
        }
    }

    public function setParams(&$params, array $arrCondition)
    {
        if(isset($arrCondition['admin_id']) && $arrCondition['admin_id']) {
            $params['admin_id'] = $arrCondition['admin_id'];
        }
        if(isset($arrCondition['status']) && $arrCondition['status']) {
            $params['status'] = $arrCondition['status'];
        }
        if(isset($arrCondition['from_date']) && $arrCondition['from_date']) {
            $params['from_date'] = ['created_at', '>=', $arrCondition['from_date'] . ' 00:00:00'];
        }
        if(isset($arrCondition['from_date']) && $arrCondition['from_date']) {
            $params['from_date'] = ['created_at', '>=', $arrCondition['from_date'] . ' 00:00:00'];
        }
        if(isset($arrCondition['to_date']) && $arrCondition['to_date']) {
            $params['to_date'] = ['created_at', '<=', $arrCondition['to_date'] . ' 23:59:59'];
        }
        if(isset($arrCondition['month']) && $arrCondition['month']) {
            $startDay = [
                date("Y"),
                $arrCondition['month'],
                '00'
            ];
            $endDay = [
                date("Y"),
                $arrCondition['month'],
                Carbon::now()->lastOfMonth()->format("d")
            ];
            $params['from_date'] = ['created_at', '>=', implode('-', $startDay) . ' 00:00:00'];
            $params['to_date'] = ['created_at', '<=', implode('-', $endDay) . ' 23:59:59'];
        }
        if(isset($arrCondition['year']) && $arrCondition['year']) {
            $startDay = Carbon::createFromFormat('Y', $arrCondition['year'])->startOfYear()->format('Y-m-d H:i:s');
            $endDay = Carbon::createFromFormat('Y', $arrCondition['year'])->endOfYear()->format('Y-m-d H:i:s');
            $params['from_date'] = ['created_at', '>=', $startDay];
            $params['to_date'] = ['created_at', '<=', $endDay];
        }
    }

    public function setParamsDefaultReport(&$arrRequest)
    {
        $arrRequest['type'] = isset($arrRequest['type']) ? $arrRequest['type'] : 'date';
        switch ($arrRequest['type']) {
            case 'date':
                $arrRequest['from_date'] = isset($arrRequest['from_date']) ? $arrRequest['from_date'] : Carbon::now()->subDays(7)->format('Y-m-d');
                $arrRequest['to_date'] = isset($arrRequest['to_date']) ? $arrRequest['to_date'] : date("Y-m-d");
                break;
            case 'month':
                $arrRequest['month'] = isset($arrRequest['month']) ? $arrRequest['month'] : date("m");
                break;
            case 'year':
                $arrRequest['year'] = isset($arrRequest['year']) ? $arrRequest['year'] : date("Y");
                break;
            default:
                break;
        }
    }
}
