<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendWebhook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'payload',
		'deleted_at'
    ];
}
