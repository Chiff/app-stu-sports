<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperNotifications
 */
class Notifications extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'entity_id',
        'html_content',
        'entity_type'
        ];

    public function type()
    {
        return $this->belongsTo(Ciselnik::class);
    }

    protected $hidden = [
        'entity_id'
    ];
}
