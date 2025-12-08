<?php

namespace App\Helpers;

use Carbon\Carbon;

class EthiopianCalendar
{
    public static function toEthiopian($gregorianDate = null)
    {
        $date = $gregorianDate ? Carbon::parse($gregorianDate) : Carbon::now();
        
        $gregorianYear = $date->year;
        // ... (rest of logic remains similar but using $date) ...
        
        // Re-implementing logic for clarity and safety inside class
        $newYearDay = 11;
        if (($gregorianYear + 1) % 4 === 0) {
            $newYearDay = 12;
        }
        
        $startOfNewYear = Carbon::create($gregorianYear, 9, $newYearDay);
        
        if ($date->lt($startOfNewYear)) {
            $ethYear = $gregorianYear - 8;
            $prevNewYearDay = 11;
            if ($gregorianYear % 4 === 0) {
                $prevNewYearDay = 12;
            }
            $startOfYear = Carbon::create($gregorianYear - 1, 9, $prevNewYearDay);
        } else {
            $ethYear = $gregorianYear - 7;
            $startOfYear = $startOfNewYear;
        }
        
        $daysPassed = $date->diffInDays($startOfYear);
        
        $ethMonth = (int)($daysPassed / 30) + 1;
        $ethDay = ($daysPassed % 30) + 1;
        
        if ($ethMonth > 13) {
            $ethMonth = 13; 
        }

        $ethiopianMonths = [
            1 => 'መስከረም', 2 => 'ጥቅምት', 3 => 'ኅዳር', 4 => 'ታኅሳስ',
            5 => 'ጥር',     6 => 'የካቲት', 7 => 'መጋቢት', 8 => 'ሚያዝያ',
            9 => 'ግንቦት',   10 => 'ሰኔ',   11 => 'ሐምሌ',   12 => 'ነሐሴ',
            13 => 'ጳጉሜን'
        ];

        $monthName = $ethiopianMonths[$ethMonth] ?? 'Unknown';

        return [
            'day'         => $ethDay,
            'month'       => $ethMonth,
            'month_name'  => $monthName,
            'year'        => $ethYear,
            'formatted'   => "$monthName $ethDay, $ethYear ዓ.ም",
        ];
    }

    public static function format($date = null): string
    {
        return self::toEthiopian($date)['formatted'];
    }
}