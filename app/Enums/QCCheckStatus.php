<?php

namespace App\Enums;

class QCCheckStatus
{
    const WAITING = 1;
    const DONE = 2;
    const FIX = 3;
    const REMAKE = 4;

    public static function all($unKey = null) {
        $statuses = [
            self::WAITING       => 'ĐANG CHỜ',
            self::DONE          => 'ĐÃ DUYỆT',
            self::FIX           => 'SỬA LẠI',
            self::REMAKE        => 'HỦY VÀ SẢN XUẤT LẠI',
        ];
        if ($unKey !== null && isset($statuses[$unKey])) {
            unset($statuses[$unKey]);
        }
        return $statuses;
    }
}
