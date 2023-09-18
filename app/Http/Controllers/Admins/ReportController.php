<?php

namespace App\Http\Controllers\Admins;

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

        $coes = $this->reportRepository->reportCo($arrRequest);
        $arrCoes = json_encode($coes->toArray());

        $paymentReceipts = $this->reportRepository->reportPaymentReceipt($arrRequest);
        $arrPaymentReceipts = json_encode($paymentReceipts);


        $bankLoans = $this->reportRepository->reportByMonthBankLoan();
        $bankLoanSummary = $this->reportRepository->summaryBankLoan();

        // table
        $tableTmpCo = $this->reportRepository->getTmpCO($arrRequest);
        $tableCo = $this->reportRepository->getCO($arrRequest);
        $tableRequest = $this->reportRepository->getRequest($arrRequest);
        $tableCustomerTmpCo = $this->reportRepository->getCustomerTmpCo($arrRequest);
        $tableCustomerCo = $this->reportRepository->getCustomerCo($arrRequest);
        $request->flash();
        return view('admins.report.index', compact('breadcrumb', 'titleForLayout', 'arrBanks', 'arrCoes',
            'arrPaymentReceipts', 'bankLoans', 'bankLoanSummary', 'tableCo', 'arrRequest', 'tableTmpCo', 'tableRequest',
            'tableCustomerTmpCo', 'tableCustomerCo'));
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