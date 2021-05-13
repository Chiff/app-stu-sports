<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCiselnik
 */
class Ciselnik extends Model
{
    protected $table = 'ciselnik';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'label',
        'group', // optional
        'type',
    ];
}
