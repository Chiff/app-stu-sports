<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperUserTeam
 */
class UserTeam extends Model {
    protected $table = 'user_team';

    protected $hidden = [
        'team_id',
        'user_id',
    ];
}
