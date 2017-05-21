<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Credit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'order_id',
		'customer_id',
		'location_id',
		'source',
		'duration',
		'purchase_datetime',
		'purchase_price',
		'expired',
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

    public function location()
    {
        return $this->hasOne('App\Location','id', 'location_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Customer','id', 'customer_id');
    }
}
