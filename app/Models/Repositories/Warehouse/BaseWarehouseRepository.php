<?php

namespace App\Models\Repositories\Warehouse;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

class BaseWarehouseRepository
{
    public $model;


    public function setModel(Model $model)
    {
        $this->model = $model;
    }
    public function create(array $data, $booleanOrModel = true)
    {
        return $booleanOrModel  ? ($this->model->create($data) ? true : false) : $this->model->create($data);
    }

    public function find(int $l_id) : Model
    {
        return $this->model->find($l_id);
    }

    public function update(int $l_id, array $data) : bool
    {
        $model = $this->find($l_id);
        if ($model) {
            return  $model->update($data);
        }

        return false;
    }

    public function delete(int $l_id) : bool
    {
        $model = $this->find($l_id);
        if ($model) {
            return $model->delete();
        }

        return false;
    }

    public function query() : Builder {
        return $this->model->query();
    }
}
