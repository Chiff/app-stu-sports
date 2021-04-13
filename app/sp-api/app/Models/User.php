<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

// TODO - 05/04/2021 - use NOTIFIABLE - https://laravel.com/api/5.5/Illuminate/Notifications/Notifiable.html
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    protected string $table = 'user';

    protected $dates = ['created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format("Y-m-d\TH:i:s");
    }


    // The attributes that are mass assignable.
    protected array $fillable = [
        'name', 'email',
    ];

    // The attributes excluded from the model's JSON form.
    protected array $hidden = [
        'ext_id',
    ];

    //Get the identifier that will be stored in the subject claim of the JWT.
    public function getJWTIdentifier(): mixed
    {
        // pokial zmenim identifikator z "id" -> https://github.com/tymondesigns/jwt-auth/issues/1103#issuecomment-475161433
        return $this->id;
    }

    // Return a key value array, containing any custom claims to be added to the JWT.
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get events, which user owns
     */
    public function ownEvents()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get events on which user is signed
     */
    public function getSignedEvents()
    {
        return $this->belongsToMany(Event::class, 'user_event');
    }

}
