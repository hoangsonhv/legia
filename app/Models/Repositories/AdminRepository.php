<?php

namespace App\Models\Repositories;

use App\Models\Admin;
use Illuminate\Support\Facades\Session;

class AdminRepository
{
    protected $model;

    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    public function getAdmins($where=array(), $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'name',
                'username',
                'mail'
            ];
            foreach ($where as $kWhere=>$vWhere)
            {
                if(is_array($vWhere) && list($field,$condition,$val) = $vWhere)
                {
                    if($condition == "like") {
                        $query = $query->where($field,$condition,"%".$val."%");
                    } else {
                        $query = $query->where($field,$condition,$val);
                    }
                } elseif(array_key_exists($kWhere, $or)) {
                    $orCond = $or[$kWhere];
                    $query = $query->where(function ($q) use ($orCond, $vWhere) {
                        foreach($orCond as $vOr) {
                            $q->orWhere($vOr, 'like', "%".$vWhere."%");
                        }
                    });
                } else {
                    $query = $query->where($kWhere,$vWhere);
                }
            }
        }
        if (!empty($opts['not_onwer'])) {
            $query = $query->where('username', '!=', 'admin');
        }
        return $query;
    }

    /**
     * Insert new model
     * @param array $arrParam
     * @return mixed
     */
    public function insert(array $arrParam){
        return $this->model->create($arrParam);
    }

    /**
     * Update exist model
     * @param array $arrParam
     * @param $id
     * @return null
     */
    public function update(array $arrParam, $id){

        $model = $this->model->where($this->model->getKeyName(), $id)->first();

        if(!$model){
            return null;
        }
        // TODO: For logging model before update

        $model->fill($arrParam)->save();

        return $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
}
