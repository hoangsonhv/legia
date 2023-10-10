<?php

namespace App\Models\Repositories\Warehouse;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

class BaseWarehouseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }
    public function create(array $data) : bool
    {
        return $this->model->create($data);
    }

    public function find(int $id) : Model
    {
        return $this->model->find($id);
    }

    public function update(int $id, array $data) : bool
    {
        $model = $this->find($id);

        if ($model) {
            $model->update($data);
            return true;
        }

        return false;
    }

    public function delete(int $id) : bool
    {
        $model = $this->find($id);

        if ($model) {
            $model->delete();
            return true;
        }

        return false;
    }
}
