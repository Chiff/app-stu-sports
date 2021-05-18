<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperEventTeam
 */
class EventTeam extends Model
{
    public $timestamps = false;
    protected $table = 'event_team';


    protected $hidden = [
        'event_id',
        'team_id'
    ];
    protected $fillable = [
        'points',
        'is_winner'
    ];
}
