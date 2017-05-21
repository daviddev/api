<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'address1',
    	'address2',
    	'company',
    	'city',
    	'province',
    	'country',
    	'zip',
    	'phone',
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

    public function customer()
    {
        return $this->hasOne('App\Customer','address_id', 'id');
    }
}
