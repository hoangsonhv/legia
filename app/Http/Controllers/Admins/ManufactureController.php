<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\AdminHelper;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRequest;
use App\Http\Requests\ManufactureRequest;
use App\Models\Admin;
use App\Models\Co;
use App\Models\CoStepHistory;
use App\Models\Manufacture;
use App\Models\Delivery;
use App\Models\ManufactureDetail;
use Illuminate\Http\Request;
use App\Models\Repositories\DeliveryRepository;
use App\Models\Repositories\ManufactureRepository;
use App\Models\Repositories\CoRepository;
use App\Enums\ProcessStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\CoStepHistoryRepository;

class ManufactureController extends Controller
{
    /**
     * @var
     */
    protected $manufactureRepo;

    /**
     * @var
     */
    protected $coManufactureRepo;

    /**
     * @var
     */
    protected $coRepo;

    /**
     * @var
     */
    protected $coStepHisRepo;

    /**
     * @var array
     */
    public $menu;

    /**
     * ManufactureController constructor.
     * @param ManufactureRepository $manufactureRepo
     * @param CoRepository $coRepo
     */
    function __construct(ManufactureRepository $manufactureRepo,
                            CoRepository $coRepo,
                         CoStepHistoryRepository $coStepHisRepo)
    {
        $this->manufactureRepo                  = $manufactureRepo;
        $this->coRepo                           = $coRepo;
        $this->coStepHisRepo                    = $coStepHisRepo;
        $this->menu                             = [
            'root' => 'Quản lý Sản xuất',
            'data' => [
                'parent' => [
                    'href'   => route('admin.manufacture.index'),
                    'label'  => 'Sản xuất'
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Danh sách'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $params                     = array();
        $limit                      = 10;
        $materialTypes              = AdminHelper::getMaterialTypes();

        // search
        if($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        if($request->has('material_type') && $request->material_type > -1) {
            $params['material_type'] = $request->material_type;
        }

        if($request->has('co_id') && $request->co_id) {
            $params['co_id'] = $request->co_id;
        }
        $coes[0] = '--- Chọn CO ---';
        $arrCoes = $this->coRepo->getCoes(['status' => ProcessStatus::Approved])->get();
        foreach ($arrCoes as $co) {
            $coes[$co->id] = $co->code;
        }

        $user = Session::get('login');
//        if(!PermissionHelper::hasPermission('admin.manufacture.index-all')) {
//            $params['admin_id'] = $user->id;
//        }

        if(PermissionHelper::hasPermission('admin.manufacture.metal') && PermissionHelper::hasPermission('admin.manufacture.non-metal')){

        } else if(PermissionHelper::hasPermission('admin.manufacture.metal')) {
            $params['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
        } else if(PermissionHelper::hasPermission('admin.manufacture.non-metal')) {
            $params['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
        }
        $datas = $this->manufactureRepo->findExtend($params)->orderBy('id','DESC')->paginate($limit);
        $request->flash();
        return view('admins.manufacture.index',compact('breadcrumb', 'titleForLayout', 'datas', 'materialTypes',
            'coes'));
    }

    public function create(Request $request)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Thêm mới'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $permissions                = config('permission.permissions');

        $params = $request->all();
        $coId = isset($params['co_id']) ? $params['co_id'] : null;
        $queryCo = $this->coRepo->getCoes([
            'id' => $coId,
            'status' => ProcessStatus::Approved,
        ])->limit(1);
        $co = $queryCo->first();
        if (!$co) {
            return redirect()->back()->with('error', 'Vui lòng kiểm tra lại CO!');
        }
        $arrCo = $queryCo->pluck('code', 'id')->toArray();
        $warehouses = $co->warehouses;
        $details = [];
        if(!empty($warehouses)) {
            foreach ($warehouses as $index => $warehouse) {
                $details[$index]['offer_price_id'] = $warehouse->id;
                $details[$index]['code'] = $warehouse->code;
                $details[$index]['loai_vat_lieu'] = $warehouse->loai_vat_lieu;
                $details[$index]['do_day'] = $warehouse->do_day;
                $details[$index]['tieu_chuan'] = $warehouse->tieu_chuan;
                $details[$index]['kich_co'] = $warehouse->kich_co;
                $details[$index]['kich_thuoc'] = $warehouse->kich_thuoc;
                $details[$index]['chuan_bich'] = $warehouse->chuan_bich;
                $details[$index]['chuan_gasket'] = $warehouse->chuan_gasket;
                $details[$index]['dv_tinh'] = $warehouse->dv_tinh;
                $details[$index]['so_luong'] = $warehouse->so_luong;
                $details[$index]['need_quantity'] = $warehouse->so_luong;
                $details[$index]['reality_quantity'] = 0;
                $details[$index]['material_type'] = $warehouse->material_type;
            }
        }
        return view('admins.manufacture.create', compact('breadcrumb', 'titleForLayout', 'permissions', 'co',
            'arrCo', 'details'));
    }

    public function store(ManufactureRequest $request)
    {
        $input = $request->input();
        $input['admin_id'] = Session::get('login')->id;
        $coId = (isset($input['co_id']) && $input['co_id']) ? $input['co_id'] : null;
        try {
            $co = $this->coRepo->find($coId);
            if (!$co) {
                return redirect()->back()->withInput()->with('error','CO không tồn tại!');
            }
            \DB::beginTransaction();

            $dataInsertMetals = [];
            $dataInsertNonMetals = [];
            if(isset($input['offer_price_id']) && is_array($input['offer_price_id'])) {
                foreach ($input['offer_price_id'] as $index => $offerPrice) {
                    $row = [
                        'offer_price_id' => $offerPrice,
                        'reality_quantity' => $input['reality_quantity'][$index],
                        'need_quantity' => $input['need_quantity'][$index],
                    ];
                    if(!isset($input['offer_price_material_type'][$index])) {
                        continue;
                    }
                    if($input['offer_price_material_type'][$index] == Manufacture::MATERIAL_TYPE_METAL) {
                        $row['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
                        array_push($dataInsertMetals, $row);
                    }
                    if($input['offer_price_material_type'][$index] == Manufacture::MATERIAL_TYPE_NON_METAL) {
                        $row['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
                        array_push($dataInsertNonMetals, $row);
                    }
                }
            }
            $input['material_type'] = Manufacture::MATERIAL_TYPE_METAL;
            $modelMetal = Manufacture::create($input);
            if($modelMetal) {
                $modelMetal->details()->createMany($dataInsertMetals);
                $this->coStepHisRepo->insertNextStep('manufacture', $modelMetal->co_id, $modelMetal->co_id, CoStepHistory::ACTION_APPROVE);
            }

            $input['material_type'] = Manufacture::MATERIAL_TYPE_NON_METAL;
            $modelNonMetal = Manufacture::create($input);
            if($modelNonMetal) {
                $modelNonMetal->details()->createMany($dataInsertNonMetals);
            }

            \DB::commit();
            return redirect()->route('admin.manufacture.index')->with('success', 'Tạo sản xuất thành công!');
        } catch(\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.manufacture.index')->with('error','Tạo sản xuất thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb                 = $this->menu;
        $breadcrumb['data']['list'] = ['label'  => 'Cập nhật'];
        $titleForLayout             = $breadcrumb['data']['list']['label'];
        $model                      = $this->manufactureRepo->find($id);
        if ($model) {
            $isCanEdit = false;
            $user = Session::get('login');
            if(!PermissionHelper::hasPermission('admin.manufacture.index-all') && $model->admin_id != $user->id) {
                $isCanEdit = false;
            }
            if($model->material_type == Manufacture::MATERIAL_TYPE_METAL) {
                $isCanEdit = PermissionHelper::hasPermission('admin.manufacture.metal');
            }
            if($model->material_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
                $isCanEdit = PermissionHelper::hasPermission('admin.manufacture.non-metal');
            }

            if(!$isCanEdit) {
                return redirect()->route('admin.manufacture.index')->with('error', 'Bạn không có quyền truy cập!');
            }

            $permissions             = config('permission.permissions');
            $coId = $model->co_id;
            if ($coId) {
                $queryCo    = $this->coRepo->getCoes([
                    'id'     => $coId,
                ])->limit(1);
                $co = $queryCo->first();
                if (!$co) {
                    return redirect()->back()->with('error','Vui lòng kiểm tra lại CO!');
                }
                $arrCo = $queryCo->pluck('code', 'id')->toArray();
            }

            $modelDetails = $model->details;
            $details = [];
            foreach ($modelDetails as $index => $detail) {
                $details[$index]['id'] = $detail->id;
                $details[$index]['offer_price_id'] = $detail->offerPrice ? $detail->offerPrice->id : '';
                $details[$index]['code'] = $detail->offerPrice ? $detail->offerPrice->code : '';
                $details[$index]['loai_vat_lieu'] = $detail->offerPrice ? $detail->offerPrice->loai_vat_lieu : '';
                $details[$index]['do_day'] = $detail->offerPrice ? $detail->offerPrice->do_day : '';
                $details[$index]['tieu_chuan'] = $detail->offerPrice ? $detail->offerPrice->tieu_chuan : '';
                $details[$index]['kich_co'] = $detail->offerPrice ? $detail->offerPrice->kich_co : '';
                $details[$index]['kich_thuoc'] = $detail->offerPrice ? $detail->offerPrice->kich_thuoc : '';
                $details[$index]['chuan_bich'] = $detail->offerPrice ? $detail->offerPrice->chuan_bich : '';
                $details[$index]['chuan_gasket'] = $detail->offerPrice ? $detail->offerPrice->chuan_gasket : '';
                $details[$index]['dv_tinh'] = $detail->offerPrice ? $detail->offerPrice->dv_tinh : '';
                $details[$index]['so_luong'] = $detail->offerPrice ? $detail->offerPrice->so_luong : '';
                $details[$index]['reality_quantity'] = $detail->reality_quantity;
                $details[$index]['need_quantity'] = $detail->need_quantity;
                $details[$index]['material_type'] = $detail->material_type;
            }

            return view('admins.manufacture.edit',compact('breadcrumb', 'titleForLayout', 'model',
                'permissions', 'co', 'arrCo', 'details'));
        }
        return redirect()->route('admin.manufacture.index')->with('error', 'Sản xuất không tồn tại!');
    }

    public function update(ManufactureRequest $request, $id)
    {
        $model = $this->manufactureRepo->find($id);
        if ($model) {
            $inputs = $request->input();
//            try {
                $co = $this->coRepo->find($model->co_id);
                if (!$co) {
                    return redirect()->back()->withInput()->with('error','CO không tồn tại!');
                }
                \DB::beginTransaction();
                $model = $this->manufactureRepo->update($inputs, $id);
                $offerPriceIds = $request->input('offer_price_id');
                if($offerPriceIds) {
                    foreach ($offerPriceIds as $index => $offerPriceId) {
                        $dataUpdate = [
                            'reality_quantity' => $inputs['reality_quantity'][$index],
                            'need_quantity' => $inputs['need_quantity'][$index],
                            'material_type' => in_array($offerPriceId, $inputs['material_type'] ?? []) ?
                                ManufactureDetail::MATERIAL_TYPE_METAL : ManufactureDetail::MATERIAL_TYPE_NON_METAL,
                            'updated_at' => Date('Y-m-d H:i:s')
                        ];
                        ManufactureDetail::updateOrCreate(['id' => $inputs['id'][$index]],$dataUpdate);
                    }
                }
                $this->manufactureRepo->updateIsCompleted($id);
                $this->manufactureRepo->checkCompleted($id);
                \DB::commit();
                return redirect()->route('admin.manufacture.edit', ['id' => $id])->with('success', 'Cập nhật sản xuất thành công!');
//            } catch(\Exception $ex) {
//                \DB::rollback();
//                report($ex);
//            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật sản xuất không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Sản xuất không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->manufactureRepo->find($id);
        if ($model) {
            $model->delete();
            $model->details()->delete();
            return redirect()->route('admin.manufacture.index')->with('success','Xóa sản xuất thành công!');
        }
        return redirect()->back()->with('error', 'Sản xuất không tồn tại!');
    }
}
