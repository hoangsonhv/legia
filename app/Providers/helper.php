<?php

use Carbon\Carbon;

if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($dateTimeString = '', $format ='H:s d-m-Y')
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString)->format($format);
    }
}
if (!function_exists('customRound')) {
    function customRound($number, $precision = 1) {
        // Tách phần nguyên và phần thập phân
        $parts = explode('.', $number);
    
        // Nếu không có phần thập phân, trả về số nguyên
        if (count($parts) == 1) {
            return $number;
        }
    
        // Phần nguyên
        $integerPart = $parts[0];
    
        // Phần thập phân
        $decimalPart = substr($parts[1], 0, $precision);
    
        // Kiểm tra phần thập phân cuối cùng
        if (strlen($parts[1]) > $precision && $parts[1][$precision] >= 5) {
            $decimalPart = (string)((int)$decimalPart + 1);
        }
    
        // Làm tròn phần thập phân nếu cần thiết
        $decimalPart = str_pad($decimalPart, $precision, '0');
    
        // Kết hợp phần nguyên với phần thập phân mới
        return $integerPart . '.' . $decimalPart;
    }
}