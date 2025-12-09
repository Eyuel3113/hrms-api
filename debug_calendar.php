<?php

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

// Mocking the class logic since we can't easily load the whole app framework in a simple script without bootstrapping
class EthiopianCalendar
{
    public static function toEthiopian($gregorianDate = null)
    {
        $date = $gregorianDate ? Carbon::parse($gregorianDate) : Carbon::now();
        
        $gregorianYear = $date->year;
        
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
        
        // DEBUG: Print details
        echo "Date: " . $date->toDateTimeString() . "\n";
        echo "StartOfYear: " . $startOfYear->toDateTimeString() . "\n";

        // ISSUE PROBABLY HERE: diffInDays behavior
        $daysPassed = $date->diffInDays($startOfYear, false); // Explicitly checking false behavior
        echo "DiffInDays(false): $daysPassed\n";
       
        $daysPassedAbs = $date->diffInDays($startOfYear);
        echo "DiffInDays(default/abs): $daysPassedAbs\n";

        // Simulate logic
        $calcDays = $daysPassedAbs; 

        $ethMonth = (int)($calcDays / 30) + 1;
        $ethDay = ($calcDays % 30) + 1;
        
        echo "EthMonth: $ethMonth\n";
        echo "EthDay: $ethDay\n";

        return [
            'day' => $ethDay,
            'month' => $ethMonth,
            'year' => $ethYear
        ];
    }
}

// Test with today
EthiopianCalendar::toEthiopian('2025-12-09');
