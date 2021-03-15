<?php

namespace App\Http\Utils;

use App\Exceptions\UnsupportedTypeException;
use DateTime;

class DateUtil
{
    public static function customDateMapper($date)
    {
        if (is_array($date)) {
            $date =
                sprintf("%04d", $date[0]) . "-" .
                sprintf("%02d", $date[1]) . "-" .
                sprintf("%02d", $date[2]) . "T" .
                sprintf("%02d", $date[3]) . ":" .
                sprintf("%02d", $date[4]) . ":" .
                sprintf("%02d", $date[5]);
        }

        if (is_numeric($date)) {
            // lebo php je prijebane a pouziva mikrosekundy :)
            $date = "@" . ($date / 1000);
        }

        if (is_string($date)) {
            return new DateTime($date);
        }

        throw new UnsupportedTypeException("neznamy typ mapovania datumu");
    }

    public static function formatDate(DateTime $value)
    {
        return $value->format("Y-m-d\TH:i:s");
    }
}
