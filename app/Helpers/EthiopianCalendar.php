<?php

if (!function_exists('toEthiopian')) {
    function toEthiopian($gregorianDate = null)
    {
        $date = $gregorianDate ? \Carbon\Carbon::parse($gregorianDate) : \Carbon\Carbon::now();

        $year  = $date->year;
        $month = $date->month;
        $day   = $date->day;

        // Ethiopian months
        $months = [
            1 => 'መስከረም', 2 => 'ጥቅምት', 3 => 'ኅዳር', 4 => 'ታኅሳስ',
            5 => 'ጥር',     6 => 'የካቲት', 7 => 'መጋቢት', 8 => 'ሚያዝያ',
            9 => 'ግንቦት',   10 => 'ሰኔ',   11 => 'ሐምሌ',   12 => 'ነሐሴ',
            13 => 'ጳጉሜን'
        ];

        // Ethiopian year calculation
        $ethiopianYear = $year - 8;
        if ($month < 9 || ($month == 9 && $day < 11)) {
            $ethiopianYear--;
        }

        // Ethiopian month/day
        if ($month == 9 && $day >= 11) {
            $ethiopianMonth = 1; // Meskerem
            $ethiopianDay   = $day - 10;
        } elseif ($month > 9) {
            $ethiopianMonth = $month - 8;
            $ethiopianDay   = $day;
        } else {
            $ethiopianMonth = $month + 4;
            $ethiopianDay   = $day;
        }

        // Pagume (13th month)
        if ($ethiopianMonth > 13) {
            $ethiopianMonth = 13;
        }

        $monthName = $months[$ethiopianMonth];

        return [
            'day'         => $ethiopianDay,
            'month'       => $ethiopianMonth,
            'month_name'  => $monthName,
            'year'        => $ethiopianYear,
            'formatted'   => "$monthName $ethiopianDay, $ethiopianYear ዓ.ም",
        ];
    }
}

if (!function_exists('formatEthiopian')) {
    function formatEthiopian($date = null): string
    {
        return toEthiopian($date)['formatted'];
    }
}