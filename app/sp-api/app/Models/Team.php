<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperTeam
 */
class Team extends Model
{
    protected $table = 'team';


    /**
     * Get users signed on the event
     */
    public function team_members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_team');
    }

    /**
     * Get users signed on the event
     */
    public function getSignedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_team');
    }

    /**
     * Get the owner of the team
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(User::class, 'user_team');
    }


    protected $fillable = [
        'team_name',
        'wins',
        'points',
        'events_total'
    ];
    protected $hidden = [];


}
