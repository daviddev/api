<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'location_id',
		'name',
		'description',
		'icon',
		'thumbnail',
		'developer',
		'release_date',
		'local_path',
		'arguments',
		'enabled',
		'steam_id',
		'video',
		'genres',
		'deleted_at'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope);
    }

    public function location()
    {
        return $this->hasOne('App\Location','id', 'location_id');
    }

    public function session()
    {
        return $this->hasMany('App\Session','game_id', 'id');
    }
}
