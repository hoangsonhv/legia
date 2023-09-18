<?php

namespace App\Models\Repositories;

use App\Models\SurveyPrice;

class SurveyPriceRepository
{
    public function __construct(SurveyPrice $surveyPrice)
    {
        $this->model = $surveyPrice;
    }

    public function find($id) 
    {
        return $this->model->find($id);
    }
}
