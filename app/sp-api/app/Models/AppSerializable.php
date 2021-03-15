<?php


namespace App\Models;

// custom serializer na urcite datove typy - zatial iba datetime
use App\Http\Utils\DateUtil;
use App\Models\Netgrif\CaseResource;
use App\Models\Netgrif\EmbededCases;
use DateTime;
use JsonSerializable;

abstract class AppSerializable implements JsonSerializable
{

    public function jsonSerialize()
    {
        $data_to_serialize = (array)$this;

        array_walk_recursive($data_to_serialize, function (&$value) {

            if ($value instanceof DateTime) {
                $value = DateUtil::formatDate($value);
            }

        });

        return $data_to_serialize;
    }
}
