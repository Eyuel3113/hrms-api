<?php

if (!function_exists('toEthiopian')) {
    function toEthiopian($gregorianDate = null)
    {
        $date = $gregorianDate ? \Carbon\Carbon::parse($gregorianDate) : \Carbon\Carbon::now();
        
        $gregorianYear = $date->year;
        $gregorianMonth = $date->month;
        $gregorianDay = $date->day;

        // Determine if the Gregorian year is a leap year (divisible by 4, unless divisible by 100 but not 400)
        $isGregorianLeap = ($gregorianYear % 4 === 0 && $gregorianYear % 100 !== 0) || ($gregorianYear % 400 === 0);
        
        // The Ethiopian new year is on Sep 11, or Sep 12 if the *following* Gregorian year is a leap year
        // However, in the context of the current year, we check if the current Gregorian year is a leap year relative to the Ethiopian calendar cycle.
        // Actually, a simpler rule: New Year is Sep 12 if ($gregorianYear + 1) % 4 == 0. Otherwise Sep 11.
        // But let's use a standard offset calculation.
        
        // Difference in days between Gregorian and Ethiopian (approx 7 or 8 years)
        // Let's use a proven algorithm based on day offsets.
        
        $ethiopianMonths = [
            1 => 'መስከረም', 2 => 'ጥቅምት', 3 => 'ኅዳር', 4 => 'ታኅሳስ',
            5 => 'ጥር',     6 => 'የካቲት', 7 => 'መጋቢት', 8 => 'ሚያዝያ',
            9 => 'ግንቦት',   10 => 'ሰኔ',   11 => 'ሐምሌ',   12 => 'ነሐሴ',
            13 => 'ጳጉሜን'
        ];

        // New Year Day (Meskerem 1)
        // It falls on September 11 usually, or September 12 in a Gregorian leap year (actually the year *before* a Gregorian leap year? No, it's about the extra day in Feb).
        // Let's use a direct conversion approach.
        
        // Constants
        $JD_EPOCH_OFFSET_AMETE_ALEM = -285019; // World Era
        $JD_EPOCH_OFFSET_AMETE_MIHRET = 1723856; // Era of Mercy (standard)
        
        // Convert Gregorian to JDN
        $a = (int)((14 - $gregorianMonth) / 12);
        $y = $gregorianYear + 4800 - $a;
        $m = $gregorianMonth + 12 * $a - 3;
        
        $jdn = $gregorianDay + (int)((153 * $m + 2) / 5) + 365 * $y + (int)($y / 4) - (int)($y / 100) + (int)($y / 400) - 32045;
        
        // Convert JDN to Ethiopian
        $r = ($jdn - $JD_EPOCH_OFFSET_AMETE_MIHRET) % 1461;
        $n = ($r % 365) + 365 * (int)($r / 1460);
        
        $ethiopianYear = 4 * (int)(($jdn - $JD_EPOCH_OFFSET_AMETE_MIHRET) / 1461) + (int)($r / 365) - (int)($r / 1460);
        $ethiopianMonth = (int)($n / 30) + 1;
        $ethiopianDay = ($n % 30) + 1;
        
        // Adjust for JDN calculation quirks
        // The above is a standard JDN conversion, but let's double check simple logic which is often safer for this specific calendar pair.
        
        // Alternative Simple Logic:
        // 1. Decide Ethiopian Year
        //    If date < Sep 11 (or 12), EthYear = GregYear - 8
        //    Else EthYear = GregYear - 7
        
        // 2. Decide New Year Date
        //    New Year is Sep 12 if (GregYear + 1) % 4 == 0. Else Sep 11.
        //    (Because the extra day in Feb happens in the *next* year of the cycle)
        
        $newYearDay = 11;
        if (($gregorianYear + 1) % 4 === 0) {
            $newYearDay = 12;
        }
        
        $startOfNewYear = \Carbon\Carbon::create($gregorianYear, 9, $newYearDay);
        
        if ($date->lt($startOfNewYear)) {
            $ethYear = $gregorianYear - 8;
            // We need the *previous* new year to calculate days passed
            $prevNewYearDay = 11;
            if ($gregorianYear % 4 === 0) {
                $prevNewYearDay = 12;
            }
            $startOfYear = \Carbon\Carbon::create($gregorianYear - 1, 9, $prevNewYearDay);
        } else {
            $ethYear = $gregorianYear - 7;
            $startOfYear = $startOfNewYear;
        }
        
        $daysPassed = $date->diffInDays($startOfYear);
        
        $ethMonth = (int)($daysPassed / 30) + 1;
        $ethDay = ($daysPassed % 30) + 1;
        
        // Cap month at 13
        if ($ethMonth > 13) {
            // Should not happen with correct logic, but safety
            $ethMonth = 13; 
        }

        $monthName = $ethiopianMonths[$ethMonth] ?? 'Unknown';

        return [
            'day'         => $ethDay,
            'month'       => $ethMonth,
            'month_name'  => $monthName,
            'year'        => $ethYear,
            'formatted'   => "$monthName $ethDay, $ethYear ዓ.ም",
        ];
    }
}

if (!function_exists('formatEthiopian')) {
    function formatEthiopian($date = null): string
    {
        return toEthiopian($date)['formatted'];
    }
}