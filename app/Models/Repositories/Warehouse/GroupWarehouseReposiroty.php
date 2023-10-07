<?php

namespace App\Models\Repositories\Warehouse;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

class GroupWarehouseReposiroty extends BaseWarehouseRepository
{
    protected $model;

    public function setModel(Model $model)
    {
        $this->model = $model;
    }
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function updateByCode(array $data, string $code): bool
    {
        $model = $this->getByCode($code);
        return $model->update($data);
    }

    public function getByCode(string $code): Model
    {
        return $this->model->where('code', $code)->first();
    }

    public function destroyByCode(string $code): bool
    {
        $model = $this->getByCode($code);
        return $model->delete();
    }
}
