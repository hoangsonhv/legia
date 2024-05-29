<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Co;
use App\Models\Repositories\ManufactureRepository;
use App\Models\Repositories\WarehouseExportSellRepository;
use App\Models\Repositories\ConfigRepository;
use App\Models\Repositories\WarehouseExportRepository;
use PDF;

class PDFController extends Controller
{
    /**
     * @var
     */
    protected $manufactureRepo;
    protected $warehouseExportSellRepository;
    protected $coManufactureRepo;
    protected $configRepository;
    protected $whExportRepo;

    /**
     * PDFController constructor.
     * @param ManufactureRepository $manufactureRepo
     */
    function __construct(
        ManufactureRepository $manufactureRepo,
        WarehouseExportSellRepository $warehouseExportSellRepository,
        ConfigRepository $configRepository,
        WarehouseExportRepository $whExportRepo
    ) {
        $this->manufactureRepo = $manufactureRepo;
        $this->warehouseExportSellRepository = $warehouseExportSellRepository;
        $this->configRepository = $configRepository;
        $this->whExportRepo = $whExportRepo;
    }

    public function manufacture($id)
    {
        try {
            $model = $this->manufactureRepo->find($id);
            if ($model) {
                $details = $model->details;
                //            return view('admins.pdf.manufacture', compact('model', 'details'));
                $pdf = PDF::loadView('admins.pdf.manufacture', compact('model', 'details'));
                return $pdf->download('Lenh_san_xuat_' . date("d-m-Y") . '.pdf');
            }
        } catch (\Exception $ex) {
            report($ex);
        }
    }

    public function manufactureCheck($id)
    {
        try {
            $model = $this->manufactureRepo->find($id);
            if ($model) {
                $details = $model->details;
                //            return view('admins.pdf.manufacture-check', compact('model', 'details'));
                $pdf = PDF::loadView('admins.pdf.manufacture-check', compact('model', 'details'));
                return $pdf->download('Phieu_kiem_tra_san_xuat_' . date("d-m-Y") . '.pdf');
            }
        } catch (\Exception $ex) {
            report($ex);
        }
    }

    public function warehouseExportSell($id)
    {
        $compName = $this->configRepository->getConfigs(['key' => 'name_company'])->first()->value;
        $compAddress = $this->configRepository->getConfigs(['key' => 'address_company'])->first()->value;
        try {
            $model = $this->warehouseExportSellRepository->find($id);
            if ($model) {
                $details = $model->products;
                //                return view('admins.pdf.warehouse-export-sell', compact('model', 'details', 'compName', 'compAddress'));
                $pdf = PDF::loadView('admins.pdf.warehouse-export-sell', compact('model', 'details', 'compName', 'compAddress'));
                return $pdf->download('Phieu_xuat_kho_ban_hang_' . date("d-m-Y") . '.pdf');
            }
        } catch (\Exception $ex) {
            report($ex);
        }
    }

    public function warehouseExport($id)
    {
        $model = $this->whExportRepo->find($id);
        $co = null;
     
        $warehouses = null;
        $listWarehouse = null;
        $coId = $model->co_id;
        if ($coId) {
            $co = Co::find($coId);  
        }
        
        $products = $model->products->toArray();
        // dd($products);
        $pdf = PDF::loadView('admins.pdf.warehouse-export', compact('model', 'products','co'));
        return $pdf->download('Phieu_xuat_kho' . date("d-m-Y") . '.pdf');
    }
}
