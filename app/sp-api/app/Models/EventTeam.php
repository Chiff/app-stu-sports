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

    protected $fillable = [
        'points',
        'is_winner',
        'event_id',
        'team_id',
    ];
}
