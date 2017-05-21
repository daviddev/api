<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Session extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'customer_id',
		'location_id',
		'game_id',
		'station_id',
		'start_datetime',
		'end_datetime',
		'duration',
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

	public function game()
    {
        return $this->hasOne('App\Game','id', 'game_id');
    }

    public function location()
    {
        return $this->hasOne('App\Location','id', 'location_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Customer','id', 'customer_id');
    }
}
