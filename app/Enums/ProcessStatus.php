<?php

namespace App\Enums;

class ProcessStatus
{
    const Pending            = 1;
    const Approved           = 2;
    const Unapproved         = 3;
    const PendingSurveyPrice = 4;
    const DoneRequest = 9;

    public static function all($unKey = null) {
        $statuses = [
            self::Pending            => 'ĐANG CHỜ XỬ LÝ',
            self::Approved           => 'ĐÃ DUYỆT',
            self::Unapproved         => 'KHÔNG DUYỆT',
            self::PendingSurveyPrice => 'ĐANG CHỜ KHẢO SÁT GIÁ',
        ];
        if ($unKey !== null && isset($statuses[$unKey])) {
            unset($statuses[$unKey]);
        } else {
            return '';
        }
        return $statuses;
    }
}
