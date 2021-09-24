<?php

namespace App\Utils;

class Calendar
{
    public function getHolidays(int $year): array
    {
        $holidays = [
            '01-01',
            '05-01',
            '05-08',
            '07-14',
            '08-15',
            '11-01',
            '11-11',
            '12-25',
            // Easter Monday = easter Sunday + 1
            date('m-d', strtotime('+1 day', \easter_date($year))),
            // Ascension Thursday = Easter Sunday + 40
            date('m-d', strtotime('+40 day', \easter_date($year))),
        ];

        if ($year < 2005) {
            // Pentecost monday = Easter Sunday + 50
            array_push(
                $holidays,
                date('m-d', strtotime('+50 day', easter_date($year)))
            );
        }

        return $holidays;
    }
}