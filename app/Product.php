<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
		'sku',
		'description',
		'credits',
		'pos_id',
		'deleted_at'
    ];
}
