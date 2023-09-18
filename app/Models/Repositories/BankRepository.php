<?php

namespace App\Models\Repositories;

use App\Enums\TransactionType;
use App\Models\Bank;
use App\Models\BankHistoryTransaction;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\Session;

class BankRepository
{
    protected $model;

    public function __construct(Bank $bank)
    {
        $this->model = $bank;
    }

    public function getBanks($where=array(), $opts=array()) 
    {
        $query = $this->model;

        if($where)
        {
            $or['key_word'] = [
                'name_bank',
                'account_name',
                'account_number'
            ];
            foreach ($where as $kWhere=>$vWhere)
            {
                if(is_array($vWhere) && list($field,$condition,$val) = $vWhere)
                {
                    if($condition == "like") {
                        $query = $query->where($field,$condition,"%".$val."%");
                    } else {
                        $query = $query->where($field,$condition,$val);
                    }
                } elseif(array_key_exists($kWhere, $or)) {
                    $orCond = $or[$kWhere];
                    $query = $query->where(function ($q) use ($orCond, $vWhere) {
                        foreach($orCond as $vOr) {
                            $q->orWhere($vOr, 'like', "%".$vWhere."%");
                        }
                    });
                } else {
                    $query = $query->where($kWhere,$vWhere);
                }
            }
        }
        return $query;
    }

    public function find($id) 
    {

        return $this->model->find($id);
    }

    public function updateAccountBalance($repository)
    {
        switch (get_class($repository)) {
            case Receipt::class:
                if($repository->payment_method == Receipt::PAYMENT_METHOD_ATM
                    && $repository->bank_id
                    && $repository->money_total) {

                    // Save account_balance of bank
                    $model = $this->find($repository->bank_id);
                    $model->account_balance += $repository->money_total;
                    $model->opening_balance += $repository->money_total;
                    $model->save();

                    // Save transactions
                    $dataTransactions = [
                        'bank_id'               => $model->id,
                        'admin_id'              => Session::get('login')->id,
                        'type'                  => TransactionType::Deposit,
                        'transaction_amount'    => $repository->money_total,
                        'current_amount'        => $model->account_balance,
                        'note'                  => $repository->note,
                        'created_at'            => Date('Y-m-d H:i:s'),
                        'updated_at'            => Date('Y-m-d H:i:s'),
                        'receipt_id'            => $repository->id,
                        'current_opening_balance' => $model->opening_balance
                    ];
                    BankHistoryTransaction::insert($dataTransactions);
                }
                break;
            case Payment::class:
                if($repository->payment_method == Receipt::PAYMENT_METHOD_ATM
                    && $repository->bank_id
                    && $repository->money_total) {

                    // Save account_balance of bank
                    $model = $this->find($repository->bank_id);
                    $model->account_balance -= $repository->money_total;
                    $model->opening_balance -= $repository->money_total;
                    $model->save();

                    // Save transactions
                    $dataTransactions = [
                        'bank_id'               => $model->id,
                        'admin_id'              => Session::get('login')->id,
                        'type'                  => TransactionType::Withdraw,
                        'transaction_amount'    => $repository->money_total,
                        'current_amount'        => $model->account_balance,
                        'note'                  => $repository->note,
                        'created_at'            => Date('Y-m-d H:i:s'),
                        'updated_at'            => Date('Y-m-d H:i:s'),
                        'payment_id'            => $repository->id,
                        'current_opening_balance' => $model->opening_balance
                    ];
                    BankHistoryTransaction::insert($dataTransactions);
                }
                break;
        }
    }

    public function checkAccountBalance($repository)
    {
        $enoughMoney = false;
        switch (get_class($repository)) {
            case Payment::class:
                if($repository->payment_method == Receipt::PAYMENT_METHOD_ATM
                    && $repository->bank_id
                    && $repository->money_total) {

                    // Check
                    $model = $this->find($repository->bank_id);
                    $enoughMoney = $model->account_balance >= $repository->money_total;
                } else {
                    return true;
                }
                break;
        }

        return $enoughMoney;
    }
}
