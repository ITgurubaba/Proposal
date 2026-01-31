<?php

use App\Helpers\Admin\BackendHelper;
use Hashids\Hashids;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

if (!function_exists('update_env')) {
    function update_env( $data = [] ) : void
    {

        $path = base_path('.env');

        if (file_exists($path)) {
            foreach ($data as $key => $value) {
                $value = Str::replace(" ","",$value);
                file_put_contents($path, str_replace(
                    $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
                ));
            }
        }

    }
}

if (! function_exists('putPermanentEnv')) {

    function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }

}

if (! function_exists('analytics_count_step_size')) {

    function analytics_count_step_size($max)
    {
        return round(($max/10 + 2.5)/5)*5;
    }
}

if (! function_exists('number_format_short')) {


    function number_format_short( $n, $precision = 1 ): string
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }

        return $n_format . $suffix;
    }

}

if (! function_exists('trim_search_keyword')) {

    function trim_search_keyword($keyword = ""): string
    {
        return Str::replace(["'",'"'],'',strip_tags($keyword));
    }
}

if (! function_exists('get_time_by_format')) {

    function get_time_by_format($time = null, $format = "d M, Y g:i A"): string
    {
        try {
            return Carbon::parse($time)->format($format);
        }
        catch (\Exception $exception)
        {
            return "";
        }
    }
}

if (! function_exists('checkData')) {

    function checkData($item = null,$array = false,$trim = false):bool
    {
        if ($array)
        {
            return $trim?isset($item) && trim($item)!="" && gettype($array) == "array":isset($item) && $item!="" && gettype($array) == "array";
        }
        return $trim?isset($item) && trim($item)!="":isset($item) && $item!="";
    }
}

if (! function_exists('collapseEmails')) {

    function collapseEmails($email):array
    {
        $emails = BackendHelper::JsonDecode(config('theme.orders_mail_groups'));
        return \Arr::collapse([
            $emails,
            [$email]
        ]);
    }
}

if (! function_exists('getMonthFormat')) {

    function getMonthFormat($month,$from = "m",$to = "F"):?string
    {
        try {
            return Carbon::createFromFormat($from,$month)->format($to);
        }
        catch (\Exception $exception){ return ""; }
    }
}

if (! function_exists('divideNumbers')) {

    function divideNumbers($first,$second,$check = false):?string
    {
        try
        {
            return $second!=0?($first/$second):0;
        }
        catch (\Exception $exception)
        {
            return $check?0:"#DIV/0!";
        }
    }
}

if (! function_exists('findPercentage')) {

    function findPercentage($amount = 0,$percentage = 95): int|float|string|null
    {
        try
        {
            return $amount * ($percentage/100);
        }
        catch (\Exception $exception)
        {
            return 0;
        }
    }
}


if (! function_exists('getFormYears')) {

    function getFormYears():array
    {
        $years = [];
        $startYear = 2015;
        $endYear = date('Y');
        for ($year = $endYear; $year >= $startYear; $year--)
        {
            $years[] = $year;
        }
        return $years;
    }
}

if (! function_exists('getMonthsArray')) {

    function getMonthsArray():array
    {
        $months = [];
        for ($i=1;$i<=12;$i++)
        {
            $months[] = $i<10?("0".$i):$i;
        }
        return $months;
    }
}

if (! function_exists('carbonDateFormat')) {

    function carbonDateFormat($time,$format,$is_created = false,$fromFormat = "Y-m-d"): string
    {
        try
        {
            return $is_created?Carbon::createFromFormat($fromFormat,$time)->format($format):
                Carbon::parse($time)->format($format);
        }
        catch (\Exception $exception)
        {
            return "";
        }
    }
}


if (!function_exists('encryptId')) {
    function encryptId($orderId): string
    {
        $hashids = new Hashids('',12);
        return $hashids->encode($orderId);
    }
}

if (!function_exists('decryptId')) {
    function decryptId($truncatedEncodedOrderId): ?string
    {
        try
        {
            $hashids = new Hashids('',12);
            $result = $hashids->decode($truncatedEncodedOrderId);
            return Arr::first($result);
        }
        catch (\Exception $exception)
        {
            return null;
        }

    }
}

if (!function_exists('getDefaultCurrencySymbol')) {
    function getDefaultCurrencySymbol(): ?string
    {
        return currency()->getCurrency(getenv('DEFAULT_CURRENCY','USD'))['symbol'] ??'$';
    }
}

if (!function_exists('themeConfig')) {
    function themeConfig($key = ""):mixed
    {
        $check = \App\Models\ThemeSetting::findByKey($key);
        return $check?->value ??null;
    }
}




