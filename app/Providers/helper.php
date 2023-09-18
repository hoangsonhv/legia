<?php

use Carbon\Carbon;

if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($dateTimeString = '', $format ='H:s d-m-Y')
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString)->format($format);
    }
}
