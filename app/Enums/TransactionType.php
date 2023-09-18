<?php

namespace App\Enums;

class TransactionType
{
    const Withdraw = 'withdraw';
    const Deposit  = 'deposit';

    public static function all() {
        return [
            self::Deposit  => 'Nạp tiền',
            self::Withdraw => 'Rút tiền',
        ];
    }
}
