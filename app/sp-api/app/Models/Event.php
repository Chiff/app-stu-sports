<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperEvent
 */
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

    protected $table = 'events';

    protected $dates = ['created_at', 'updated_at', 'registration_start', 'registration_end', 'event_start', 'event_end'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format("Y-m-d\TH:i:s");
    }

    /**
     * Get ovner of the event
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get users signed on the event
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'user_event');
    }

    /**
     * Get teams signed to event
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'event_team');
    }

    /**
     * Get teams signed to event
     */
    public function type()
    {
        return $this->belongsTo(Ciselnik::class);
    }

    protected $fillable = [
        'ext_id',
        'user_id',
        'created_at',
        'updated_at',
        'name',
        'registration_start',
        'registration_end',
        'event_start',
        'event_end',
        'min_teams',
        'max_teams',
        'min_team_members',
        'max_team_members',
        'description',
        'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'ext_id'
    ];
}
