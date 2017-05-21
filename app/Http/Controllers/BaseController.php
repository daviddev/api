<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
	protected $ajax = false;

    public function __construct(Request $request)
    {
    	if($request->ajax())
    		$this->ajax = true;
    }
}
