<?php
namespace App\Exports;

use App\Models\Manufacture;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ManufactureExport implements FromView
{
    public function __construct(Manufacture $model)
    {
        $this->model = $model;
    }

    public function view(): View
    {
        // TODO: Implement view() method.
        $model = $this->model;
        $datas = $model->details;
        return view('admins.exports.manufacture', compact('datas'));
    }
}