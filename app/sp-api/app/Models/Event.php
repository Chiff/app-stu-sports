<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

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
}
