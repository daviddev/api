<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

use Carbon\Carbon;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'company_id',
		'name',
		'description',
		'address1',
		'address2',
		'city',
		'first_name',
		'last_name',
		'phone',
		'province',
		'country',
		'zip',
		'pos_id',
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

    public function company()
    {
        return $this->hasOne('App\Company','id', 'company_id');
    }

    public function credits()
    {
        return $this->hasMany('App\Credit','location_id', 'id');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer','location_id', 'id');
    }

    public function games()
    {
        return $this->hasMany('App\Game','location_id', 'id');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session','location_id', 'id')->orderBy('start_datetime', 'desc');
    }

    public function sessionsToday()
    {
        return $this->hasMany('App\Session','location_id', 'id')->where('start_datetime', '>', Carbon::yesterday())->orderBy('start_datetime', 'desc');
    }

    public function sessionsLastMonth()
    {
        return $this->hasMany('App\Session','location_id', 'id')->where('start_datetime', '>', Carbon::now()->subMonth())->orderBy('start_datetime', 'desc');
    }
}
