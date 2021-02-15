<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Event extends Model
{
    // ani jedno riesenie vsak nie je ok, lebo toto budeme vzdy musiet definovat, taktiez pokial bude mat Model DATE aj DATETIME aj TIME tak musime pouzit cast
    // TODO - 15/02/2021 - mozno toto https://carbon.nesbot.com/docs/

    // alternativa
    // https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting
    // protected $casts = [
    //     'created_at' => 'datetime:Y-m-d\TH:i:s',
    // ];

    // https://laravel.com/docs/8.x/eloquent-serialization#date-serialization
    protected $dates = ['created_at', 'updated_at', 'registration_start', 'registration_end', 'event_start', 'event_end'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format("Y-m-d\TH:i:s");
    }

    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'registration_start',
        'registration_end',
        'event_start',
        'event_end',
        'max_participants',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected static function showEvents() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://engine.interes.group//api/task",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS =>"",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json;charset=UTF-8",
                "Authorization: Basic eHJ5YmFybUBzdHViYS5zazo4MDA4Nw==",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }
}
