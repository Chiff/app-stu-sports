<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected string $table = 'team';


    /**
     * Get users signed on the event
     */
    public function team_members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_team');
    }

    /**
     * Get users signed on the event
     */
    public function getSignedEvents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_team');
    }

    /**
     * Get the owner of the team
     */
    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    protected array $fillable = [
        'team_name'
    ];
    protected array $hidden = [];


}
