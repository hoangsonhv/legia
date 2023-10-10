<?php

namespace App\Services;

use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\WarehouseHelper;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;

class WarehouseService
{
    protected $baseWarehouseReposiroty;
    public function __construct(BaseWarehouseRepository $baseWarehouseReposiroty){
        $this->baseWarehouseReposiroty = $baseWarehouseReposiroty;
    }
    public function storeOrUpdate(string $model, array $data) : bool {
        try {
            if (!empty($data['date'])) {
                $data['date'] = AdminHelper::convertDate($data['date']);
            } else {
                $data['date'] = null;
            }
            switch ($model) {
                case 'bia':
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::BIA));
                    $data['model_type'] = WarehouseHelper::BIA;
                case 'caosuvnza':
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CAO_SU_VN_ZA));
                    $data['model_type'] = WarehouseHelper::CAO_SU_VN_ZA;
                case 'caosu':
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CAO_SU));
                    $data['model_type'] = WarehouseHelper::CAO_SU;
                case 'ceramic':
                    $data['model_type'] = WarehouseHelper::CREAMIC;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CREAMIC));
                case 'graphite':
                    $data['model_type'] = WarehouseHelper::GRAPHITE;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GRAPHITE));
                case 'ptfe':
                    $data['model_type'] = WarehouseHelper::PTFE;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE));
                case 'tamkimloai':
                    $data['model_type'] = WarehouseHelper::TAM_KIM_LOAI;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::TAM_KIM_LOAI));
                case 'tamnhua':
                    $data['model_type'] = WarehouseHelper::TAM_NHUA;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::TAM_NHUA));
                case 'filler':
                    $data["model_type"] = WarehouseHelper::FILLER;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::FILLER));
                case 'glandpackinglatty':
                    $data["model_type"] = WarehouseHelper::GLAND_PACKING_LATTY;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING_LATTY));
                case 'hoop':
                    $data["model_type"] = WarehouseHelper::HOOP;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::HOOP));
                case 'oring':
                    $data["model_type"] = WarehouseHelper::ORING;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ORING));
                case 'ptfeenvelope':
                    $data["model_type"] = WarehouseHelper::PTFE_ENVELOP;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_ENVELOP));
                case 'ptfetape':
                    $data["model_type"] = WarehouseHelper::PTFE_TAPE;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_TAPE));
                case 'rtj':
                    $data["model_type"] = WarehouseHelper::RTJ;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::RTJ));
                case 'thanhphamswg':
                    $data["model_type"] = WarehouseHelper::THANH_PHAM_SWG;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::THANH_PHAM_SWG));
                case 'vanhtinhinnerswg':
                    $data["model_type"] = WarehouseHelper::VANH_TINH_INNER_SWG;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_INNER_SWG));
                case 'vanhtinhouterswg':
                    $data["model_type"] = WarehouseHelper::VANH_TINH_OUTER_SWG;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_OUTER_SWG));
                case 'ccdc':
                    $data["model_type"] = WarehouseHelper::CCDC;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CCDC));
                case 'daycaosusilicone':
                    $data["model_type"] = WarehouseHelper::DAY_CAO_SU_VA_SILICON;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::DAY_CAO_SU_VA_SILICON));
                case 'dayceramic':
                    $data["model_type"] = WarehouseHelper::DAY_CREAMIC;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::DAY_CREAMIC));
                case 'glandpacking':
                    $data["model_type"] = WarehouseHelper::GLAND_PACKING;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING));
                case 'nhuakythuatcayong':
                    $data["model_type"] = WarehouseHelper::NHU_KY_THUAT_CAY_ONG;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::NHU_KY_THUAT_CAY_ONG));
                case 'ongglassepoxy':
                    $data["model_type"] = WarehouseHelper::ONG_GLASS_EXPOXY;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ONG_GLASS_EXPOXY));
                case 'phutungdungcu':
                    $data["model_type"] = WarehouseHelper::PHU_TUNG_DUNG_CU;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PHU_TUNG_DUNG_CU));
                case 'ptfecayong':
                    $data["model_type"] = WarehouseHelper::PTFE_CAYONG;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_CAYONG));
                case 'ndloaikhac':
                    $data["model_type"] = WarehouseHelper::ND_LOAI_KHAC;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ND_LOAI_KHAC));
                case 'nkloaikhac':
                    $data["model_type"] = WarehouseHelper::NK_LOAI_KHAC;
                    $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::NK_LOAI_KHAC));
                default:
                    return false;
            }
            if(isset($data["l_id"]) && !empty($data["l_id"]))
            {
                return $this->baseWarehouseReposiroty->update($data["l_id"],$data);
            }
            else return $this->baseWarehouseReposiroty->create($data);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    
}
