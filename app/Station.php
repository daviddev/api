<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Station extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'location_id',
		'name',
        'type',
		'equipment_serial',
		'width',
		'depth',
		'position',
		'customer_id',
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

    public function customer()
    {
        return $this->hasOne('App\Customer','id', 'customer_id');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session','station_id', 'id');
    }
}
