<?php
namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\DataHelper;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Imports\Warehouse\WarehouseImport;
use App\Models\CoTmp;
use App\Models\Repositories\ConfigRepository;
use App\Models\Repositories\RequestRepository;
use App\Models\Repositories\CoTmpRepository;
use App\Models\Repositories\CoRepository;
use App\Models\Repositories\Warehouse\GroupWarehouseReposiroty;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller {

    protected $requestRepository;


    /**
     * @var
     */
    protected $coTmpRepo;

    /**
     * @var
     */
    protected $coRepo;

    /**
     * @var
     */
    protected $configRepository;

	public $menu;

	public function __construct(RequestRepository $requestRepository,
                                CoTmpRepository $coTmpRepo,
                                CoRepository $coRepo,
                                ConfigRepository $configRepository
                                )
	{
        $this->requestRepository    = $requestRepository;
        $this->coTmpRepo            = $coTmpRepo;
        $this->coRepo               = $coRepo;
        $this->configRepository     = $configRepository;
        $this->menu                 = [
			'root' => 'Tổng quan',
			'data' => []
		];
	}

	public function index(Request $request)
	{

	}

    public function coTmp(Request $request)
    {
         // $warehouse = WarehouseHelper::getModel(WarehouseHelper::BIA);
        // // dd( $warehouse);
        // $warehouse->create([
        //     'code' => '12',
        //     'vat_lieu' => '12',
        //     'do_day' => '12',
        //     'hinh_dang' => '12',
        //     'dia_w_w1' => '12',
        //     'l_l1' => '12',
        //     'w2' =>'12',
        //     'l2' => '12',
        //     'sl_tam' => '12',
        //     'sl_m2' => '12',
        //     'lot_no' => '12',
        //     'ghi_chu' => '12',
        //     'date' => now(),
        //     'ton_sl_tam' =>'12',
        //     'ton_sl_m2' =>'12',
        //     'model_type' => '1'
        // ]);
        $breadcrumb     = $this->menu;
        $titleForLayout = $this->menu['root'];
        $titleForChart  = 'Thống kê nguyên vật liệu';

        $listRangeDate  = [
            'weeks'  => 'Biều đồ trên tuần',
            'months' => 'Biểu đồ trên tháng'
		];
        $rangeDate = $request->input('range_date');
        if (!$rangeDate) {
            $rangeDate = 'weeks';
        }

        $last = Carbon::parse("Now -1 {$rangeDate}");
        $now  = Carbon::now();

        $code = '';
        if(isset($request->code))
        {
            $code = $request->code;
        }

        // $queryDataset = $this->requestRepository->getRequests([
        //         'category' => DataHelper::KHO.'-nguyen_vat_lieu',
        //         'created_at' => [ 'requests.created_at', 'between', [$last->format('Y-m-d H:i:s'), $now->format('Y-m-d H:i:s')] ],
        //     ])
        //     ->join('request_materials', 'requests.id', 'request_materials.request_id')
        //     ->groupBy('request_materials.code')
        //     ->orderBy('dataset', 'DESC')
        //     ->limit(10)
        //     ->select(
        //         'request_materials.code',
        //         DB::raw('SUM(request_materials.dinh_luong) as dataset')
        //     );
            // ->groupBy('label')
            // ->select(    // Số lượng nguyên vật liệu đã thêm vào phiếu yêu cầu
            //     DB::raw('DATE_FORMAT(requests.created_at, "%Y-%m-%d") as label'),
            //     DB::raw('SUM(request_materials.dinh_luong) as dataset')
            // );

        // $queryDataset = DB::table('requests')
        // $tmpData = $queryDataset->pluck('dataset', 'code')->toArray();
        // if ($tmpData) {
        //     $data['labels']     = array_keys($tmpData);
        //     $datasets           = array_values($tmpData);
        //     $data['datasets'][] = [
        //         'label'           => 'Số lượng mua nhiều nhất',
        //         'backgroundColor' => 'rgba(0, 0, 0, 0)',
        //         'borderColor'     => 'rgba(60,141,188,0.8)',
        //         'data'            => $datasets
        //     ];
        //     $data = json_encode($data);
        // } else {
        //     $data = json_encode([
        //         'labels'   => [],
        //         'datasets' => []
        //     ]);
        // }
        $request->flash();

        // $coTmps = $this->coTmpRepo->getCoes([
        //     'status' => ProcessStatus::Approved,
        // ])->orderBy('created_at', 'ASC')
        // ->where(function ($query) {
        //     $query = $query->whereRaw(DB::raw("co_id = 0 OR (co_not_approved_id > 0 AND status = 1)"));
        // })->paginate();
        $limitApprovalCg = $this->configRepository->getConfigs(['key' => 'limit_approval_cg'])->first()->value;

        $coTmps = CoTmp::select('co_tmps.*')
        ->when($code != '',function($sql) use ($code){
            $sql = $sql->where('co_tmps.code', 'like', "%$code%");})
        ->leftJoin('co', 'co.id', 'co_tmps.co_id')
        ->where('co_tmps.co_id', 0)
        ->where(function($sql) use ($limitApprovalCg) {
            $sql = $sql->where('co_tmps.status', '<=', ProcessStatus::Approved)
                ->orWhere('co_tmps.tong_gia', '<', $limitApprovalCg);
        })->orWhere(function($sql) {
            $sql = $sql->where('co_tmps.co_not_approved_id', '>', 0)
                ->where('co.status', ProcessStatus::Unapproved);
        })
        ->orderBy('created_at', 'DESC')->paginate(10);

        $coes = $this->coRepo->getCoes([
            'confirm_done' => 0,
            ['status', '!=', ProcessStatus::Unapproved]
        ])->orderBy('created_at', 'ASC')->paginate(50);
		return view('admins.dashboard.co.quotation', compact('breadcrumb', 'titleForLayout', 'titleForChart',
            'listRangeDate', 'coTmps', 'coes', 'limitApprovalCg'));
    }

    public function co(Request $request)
    {
         // $warehouse = WarehouseHelper::getModel(WarehouseHelper::BIA);
        // // dd( $warehouse);
        // $warehouse->create([
        //     'code' => '12',
        //     'vat_lieu' => '12',
        //     'do_day' => '12',
        //     'hinh_dang' => '12',
        //     'dia_w_w1' => '12',
        //     'l_l1' => '12',
        //     'w2' =>'12',
        //     'l2' => '12',
        //     'sl_tam' => '12',
        //     'sl_m2' => '12',
        //     'lot_no' => '12',
        //     'ghi_chu' => '12',
        //     'date' => now(),
        //     'ton_sl_tam' =>'12',
        //     'ton_sl_m2' =>'12',
        //     'model_type' => '1'
        // ]);
        $breadcrumb     = $this->menu;
        $titleForLayout = $this->menu['root'];
        $titleForChart  = 'Thống kê nguyên vật liệu';

        $listRangeDate  = [
            'weeks'  => 'Biều đồ trên tuần',
            'months' => 'Biểu đồ trên tháng'
		];
        $rangeDate = $request->input('range_date');
        if (!$rangeDate) {
            $rangeDate = 'weeks';
        }

        $code = '';
        if(isset($request->code))
        {
            $code = $request->code;
        }

        $last = Carbon::parse("Now -1 {$rangeDate}");
        $now  = Carbon::now();
        // $queryDataset = $this->requestRepository->getRequests([
        //         'category' => DataHelper::KHO.'-nguyen_vat_lieu',
        //         'created_at' => [ 'requests.created_at', 'between', [$last->format('Y-m-d H:i:s'), $now->format('Y-m-d H:i:s')] ],
        //     ])
        //     ->join('request_materials', 'requests.id', 'request_materials.request_id')
        //     ->groupBy('request_materials.code')
        //     ->orderBy('dataset', 'DESC')
        //     ->limit(10)
        //     ->select(
        //         'request_materials.code',
        //         DB::raw('SUM(request_materials.dinh_luong) as dataset')
        //     );
            // ->groupBy('label')
            // ->select(    // Số lượng nguyên vật liệu đã thêm vào phiếu yêu cầu
            //     DB::raw('DATE_FORMAT(requests.created_at, "%Y-%m-%d") as label'),
            //     DB::raw('SUM(request_materials.dinh_luong) as dataset')
            // );

        // $queryDataset = DB::table('requests')
        // $tmpData = $queryDataset->pluck('dataset', 'code')->toArray();
        // if ($tmpData) {
        //     $data['labels']     = array_keys($tmpData);
        //     $datasets           = array_values($tmpData);
        //     $data['datasets'][] = [
        //         'label'           => 'Số lượng mua nhiều nhất',
        //         'backgroundColor' => 'rgba(0, 0, 0, 0)',
        //         'borderColor'     => 'rgba(60,141,188,0.8)',
        //         'data'            => $datasets
        //     ];
        //     $data = json_encode($data);
        // } else {
        //     $data = json_encode([
        //         'labels'   => [],
        //         'datasets' => []
        //     ]);
        // }
        $request->flash();

        // $coTmps = $this->coTmpRepo->getCoes([
        //     'status' => ProcessStatus::Approved,
        // ])->orderBy('created_at', 'ASC')
        // ->where(function ($query) {
        //     $query = $query->whereRaw(DB::raw("co_id = 0 OR (co_not_approved_id > 0 AND status = 1)"));
        // })->paginate();

        $coTmps = CoTmp::select('co_tmps.*')->leftJoin('co', 'co.id', 'co_tmps.co_id')
        ->where(function($sql) {
            $sql = $sql->where('co_tmps.status', ProcessStatus::Approved)
                ->where('co_tmps.co_id', 0);
        })->orWhere(function($sql) {
            $sql = $sql->where('co_tmps.co_not_approved_id', '>', 0)
                ->where('co.status', ProcessStatus::Unapproved);
        })->get();
        if($code)
        {
            $coes = $this->coRepo->getCoes([
                'confirm_done' => 0,
                ['status', '!=', ProcessStatus::Unapproved],
                ['code' ,'like', $code]
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }
        else {
            $coes = $this->coRepo->getCoes([
                'confirm_done' => 0,
                ['status', '!=', ProcessStatus::Unapproved],
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }

		return view('admins.dashboard.co.list', compact('breadcrumb', 'titleForLayout', 'titleForChart',
            'listRangeDate', 'coTmps', 'coes'));
    }

    public function requests(Request $request) {
        $breadcrumb     = $this->menu;
        $titleForLayout = $this->menu['root'];
        $titleForChart  = 'Thống kê nguyên vật liệu';
        $categories    = DataHelper::getCategoriesForIndex([DataHelper::VAN_PHONG_PHAM, DataHelper::DINH_KY, DataHelper::HOAT_DONG]);
        $listRangeDate  = [
            'weeks'  => 'Biều đồ trên tuần',
            'months' => 'Biểu đồ trên tháng'
		];
        $rangeDate = $request->input('range_date');
        if (!$rangeDate) {
            $rangeDate = 'weeks';
        }

        $code = '';
        if(isset($request->code))
        {
            $code = $request->code;
        }

        $last = Carbon::parse("Now -1 {$rangeDate}");
        $now  = Carbon::now();
        $request->flash();
        if($code)
        {
            $requests = $this->requestRepository->getRequests([
                ['status', '!=', ProcessStatus::DoneRequest],
                ['category', 'in', array_keys($categories)],
                ['code' ,'like', $code]
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }
        else {
            $requests = $this->requestRepository->getRequests([
                ['status', '!=', ProcessStatus::DoneRequest],
                ['category', 'in', array_keys($categories)]
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('admins.dashboard.request.list', compact('breadcrumb', 'titleForLayout', 'titleForChart',
            'listRangeDate', 'requests'));
    }

    public function trekingCo(Request $request)
    {
        $breadcrumb     = $this->menu;
        $titleForLayout = $this->menu['root'];
        $titleForChart  = 'Thống kê nguyên vật liệu';

        $listRangeDate  = [
            'weeks'  => 'Biều đồ trên tuần',
            'months' => 'Biểu đồ trên tháng'
		];
        $rangeDate = $request->input('range_date');
        if (!$rangeDate) {
            $rangeDate = 'weeks';
        }

        $code = '';
        if(isset($request->code))
        {
            $code = $request->code;
        }

        $last = Carbon::parse("Now -1 {$rangeDate}");
        $now  = Carbon::now();
        $request->flash();
        $coTmps = CoTmp::select('co_tmps.*')->leftJoin('co', 'co.id', 'co_tmps.co_id')
        ->where(function($sql) {
            $sql = $sql->where('co_tmps.status', ProcessStatus::Approved)
                ->where('co_tmps.co_id', 0);
        })->orWhere(function($sql) {
            $sql = $sql->where('co_tmps.co_not_approved_id', '>', 0)
                ->where('co.status', ProcessStatus::Unapproved);
        })->get();
        if($code)
        {
            $coes = $this->coRepo->getCoes([
                'confirm_done' => 0,
                ['status', '!=', ProcessStatus::Unapproved],
                ['code' ,'like', $code]
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }
        else {
            $coes = $this->coRepo->getCoes([
                'confirm_done' => 0,
                ['status', '!=', ProcessStatus::Unapproved],
            ])->orderBy('created_at', 'DESC')->paginate(10);
        }

		return view('admins.dashboard.treking.list', compact('breadcrumb', 'titleForLayout', 'titleForChart',
            'listRangeDate', 'coTmps', 'coes'));
    }
}
