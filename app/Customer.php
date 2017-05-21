<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'location_id',
		'email',
		'accepts_marketing',
		'first_name',
		'last_name',
        'note',
		'address_id',
		'state',
		'verified_email',
		'tax_exempt',
		'tags',
		'pos_id',
		'sex',
		'date_of_birth',
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

    public function sessions()
    {
        return $this->hasMany('App\Session','customer_id', 'id')->orderBy('start_datetime', 'desc');
    }

    public function sessionsWithoutOrder()
    {
        return $this->hasMany('App\Session','customer_id', 'id');
    }

    public function credits()
    {
        return $this->hasMany('App\Credit','customer_id', 'id');
    }

    public function pings()
    {
        return $this->hasMany('App\Ping','customer_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Address','id', 'address_id');
    }
}
