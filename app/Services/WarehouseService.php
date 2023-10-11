<?php

namespace App\Services;

use App\Helpers\AdminHelper;
use App\Helpers\DataHelper;
use App\Helpers\WarehouseHelper;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Throw_;

class WarehouseService
{
    protected $baseWarehouseReposiroty;

    private $modelType = '';
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
            $this->setRepository($model);
            $data['model_type'] = $this->modelType;
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

    public function edit(int $l_id, string $model) : Model | null {
   
        $this->setRepository($model);
        $model = $this->baseWarehouseReposiroty->find($l_id);
        return $model ? $model : null;
    }
    public function setRepository($model)
    {
        switch ($model) {
            case 'bia':
                $this->modelType = WarehouseHelper::BIA;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::BIA));
                break;
            case 'caosuvnza':
                $this->modelType = WarehouseHelper::CAO_SU_VN_ZA;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CAO_SU_VN_ZA));
                break;
            case 'caosu':
                $this->modelType = WarehouseHelper::CAO_SU;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CAO_SU));
                break;
            case 'ceramic':
                $this->modelType = WarehouseHelper::CREAMIC;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CREAMIC));
                break;
            case 'graphite':
                $this->modelType = WarehouseHelper::GRAPHITE;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GRAPHITE));
                break;
            case 'ptfe':
                $this->modelType = WarehouseHelper::PTFE;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE));
                break;
            case 'tamkimloai':
                $this->modelType = WarehouseHelper::TAM_KIM_LOAI;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::TAM_KIM_LOAI));
                break;
            case 'tamnhua':
                $this->modelType = WarehouseHelper::TAM_NHUA;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::TAM_NHUA));
                break;
            case 'filler':
                $this->modelType = WarehouseHelper::FILLER;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::FILLER));
                break;
            case 'glandpackinglatty':
                $this->modelType = WarehouseHelper::GLAND_PACKING_LATTY;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING_LATTY));
                break;
            case 'hoop':
                $this->modelType = WarehouseHelper::HOOP;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::HOOP));
                break;
            case 'oring':
                $this->modelType = WarehouseHelper::ORING;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ORING));
                break;
            case 'ptfeenvelope':
                $this->modelType = WarehouseHelper::PTFE_ENVELOP;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_ENVELOP));
                break;
            case 'ptfetape':
                $this->modelType = WarehouseHelper::PTFE_TAPE;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_TAPE));
                break;
            case 'rtj':
                $this->modelType = WarehouseHelper::RTJ;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::RTJ));
                break;
            case 'thanhphamswg':
                $this->modelType = WarehouseHelper::THANH_PHAM_SWG;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::THANH_PHAM_SWG));
                break;
            case 'vanhtinhinnerswg':
                $this->modelType = WarehouseHelper::VANH_TINH_INNER_SWG;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_INNER_SWG));
                break;
            case 'vanhtinhouterswg':
                $this->modelType = WarehouseHelper::VANH_TINH_OUTER_SWG;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::VANH_TINH_OUTER_SWG));
                break;
            case 'ccdc':
                $this->modelType = WarehouseHelper::CCDC;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::CCDC));
                break;
            case 'daycaosusilicone':
                $this->modelType = WarehouseHelper::DAY_CAO_SU_VA_SILICON;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::DAY_CAO_SU_VA_SILICON));
                break;
            case 'dayceramic':
                $this->modelType = WarehouseHelper::DAY_CREAMIC;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::DAY_CREAMIC));
                break;
            case 'glandpacking':
                $this->modelType = WarehouseHelper::GLAND_PACKING;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::GLAND_PACKING));
                break;
            case 'nhuakythuatcayong':
                $this->modelType = WarehouseHelper::NHU_KY_THUAT_CAY_ONG;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::NHU_KY_THUAT_CAY_ONG));
                break;
            case 'ongglassepoxy':
                $this->modelType = WarehouseHelper::ONG_GLASS_EXPOXY;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ONG_GLASS_EXPOXY));
                break;
            case 'phutungdungcu':
                $this->modelType = WarehouseHelper::PHU_TUNG_DUNG_CU;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PHU_TUNG_DUNG_CU));
                break;
            case 'ptfecayong':
                $this->modelType = WarehouseHelper::PTFE_CAYONG;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::PTFE_CAYONG));
                break;
            case 'ndloaikhac':
                $this->modelType = WarehouseHelper::ND_LOAI_KHAC;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::ND_LOAI_KHAC));
                break;
            case 'nkloaikhac':
                $this->modelType = WarehouseHelper::NK_LOAI_KHAC;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::NK_LOAI_KHAC));
                break;
            case 'tpphikimloai':
                $this->modelType = WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI;
                $this->baseWarehouseReposiroty->setModel(WarehouseHelper::getModel(WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI));
                break;
            default:
                throw new Exception("Not Found Model");
        }
    }

    public function delete(int $l_id,$model) : bool
    {
        $this->setRepository($model);
        return $this->baseWarehouseReposiroty->delete($l_id);
    }

    public function search($model, $params) {
        $this->setRepository($model);
        $query = $this->baseWarehouseReposiroty->query();
        if(isset($params['key_word']))
        {
            $keyword = $params['key_word'];
            $query->where('code','like',"%$keyword%");
        }
        $query->where('model_type',$this->modelType);
        $query->orderBy('l_id','DESC');
        return $query->paginate($this->paginate());
    }

    public function paginate(): int {
        return 15;
    }
}
