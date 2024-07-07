<?php

use Carbon\Carbon;

if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($dateTimeString = '', $format ='H:s d-m-Y')
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString)->format($format);
    }
}
if (!function_exists('customRound')) {
    function customRound($number) {
        // Tách phần nguyên và phần thập phân
        $parts = explode('.', $number);
    
        // Nếu không có phần thập phân, trả về số nguyên
        if (count($parts) == 1) {
            return $number;
        }
    
        // Phần nguyên
        $integerPart = $parts[0];
    
        // Phần thập phân đầu tiên
        $decimalPart = $parts[1][0];
    
        // Kiểm tra phần thập phân đầu tiên
        if ($decimalPart >= 5) {
            $newDecimal = 5;
        } else {
            $newDecimal = 0;
        }
    
        // Kết hợp phần nguyên với phần thập phân mới
        return $integerPart . '.' . $newDecimal;
    }
}