<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'session_id',
		'customer_id',
		'duration',
		'location_id',
		'game_id',
		'station_id',
		'ping_datetime',
		'deleted_at'
    ];

    public function location()
    {
        return $this->hasOne('App\Location','id', 'location_id');
    }
}
