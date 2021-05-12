<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Carbon;

    /**
 * App\Models\Event
 *
 * @mixin IdeHelperEvent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property Carbon $registration_start
 * @property Carbon $registration_end
 * @property Carbon $event_start
 * @property Carbon|null $event_end
 * @property string $ext_id
 * @property int $min_teams
 * @property int $max_teams
 * @property int $min_team_members
 * @property int $max_team_members
 * @property int $user_id
 * @property-read User $owner
 * @property-read Collection|User[] $participants
 * @property-read int|null $participants_count
 * @property-read Collection|Team[] $teams
 * @property-read int|null $teams_count
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereEventEnd($value)
 * @method static Builder|Event whereEventStart($value)
 * @method static Builder|Event whereExtId($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereMaxTeamMembers($value)
 * @method static Builder|Event whereMaxTeams($value)
 * @method static Builder|Event whereMinTeamMembers($value)
 * @method static Builder|Event whereMinTeams($value)
 * @method static Builder|Event whereName($value)
 * @method static Builder|Event whereRegistrationEnd($value)
 * @method static Builder|Event whereRegistrationStart($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUserId($value)
 */
	class IdeHelperEvent extends Eloquent {}
}

namespace App\Models{

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;

    /**
 * App\Models\EventTeam
 *
 * @mixin IdeHelperEventTeam
 * @method static Builder|EventTeam newModelQuery()
 * @method static Builder|EventTeam newQuery()
 * @method static Builder|EventTeam query()
 */
	class IdeHelperEventTeam extends Eloquent {}
}

namespace App\Models{

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Carbon;

    /**
 * App\Models\Team
 *
 * @mixin IdeHelperTeam
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property string $team_name
 * @property-read User $owner
 * @property-read Collection|User[] $teamMembers
 * @property-read int|null $team_members_count
 * @property-read Collection|User[] $team_members
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereTeamName($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @method static Builder|Team whereUserId($value)
 */
	class IdeHelperTeam extends Eloquent {}
}

namespace App\Models{

    use Database\Factories\UserFactory;
    use Eloquent;
    use Illuminate\Contracts\Auth\Access\Authorizable;
    use Illuminate\Contracts\Auth\Authenticatable;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Carbon;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    /**
 * App\Models\User
 *
 * @mixin IdeHelperUser
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $firstname
 * @property string $surname
 * @property string $email
 * @property int $ext_id
 * @property string|null $encrypted_auth
 * @property-read Collection|Event[] $ownEvents
 * @property-read int|null $own_events_count
 * @property-read Collection|Team[] $ownTeams
 * @property-read int|null $own_teams_count
 * @property-read Collection|Team[] $teams
 * @property-read int|null $teams_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEncryptedAuth($value)
 * @method static Builder|User whereExtId($value)
 * @method static Builder|User whereFirstname($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereSurname($value)
 * @method static Builder|User whereUpdatedAt($value)
 */
	class IdeHelperUser extends Eloquent implements Authenticatable, Authorizable, JWTSubject {}
}

namespace App\Models{

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;

    /**
 * App\Models\UserEvent
 *
 * @mixin IdeHelperUserEvent
 * @method static Builder|UserEvent newModelQuery()
 * @method static Builder|UserEvent newQuery()
 * @method static Builder|UserEvent query()
 */
	class IdeHelperUserEvent extends Eloquent {}
}

namespace App\Models{

    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;

    /**
 * App\Models\UserTeam
 *
 * @mixin IdeHelperUserTeam
 * @method static Builder|UserTeam newModelQuery()
 * @method static Builder|UserTeam newQuery()
 * @method static Builder|UserTeam query()
 */
	class IdeHelperUserTeam extends Eloquent {}
}

