<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

### Requests ###
use App\Http\Requests\PingRequest;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\PingsInterface;

class PingsController extends BaseController
{
    public function __construct(Request $request,
                                PingsInterface $pingRepo)
    {
        parent::__construct($request);
        $this->pingRepo = $pingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        if ($user->role == 'super-admin')
            $pings = $this->pingRepo->getAll();
        elseif ($user->role == 'admin')
            $pings = $this->pingRepo->getUserPings($user->id);
        else
            $pings = $this->pingRepo->getLocationPings($user->location_id);

        return response()->data($this->ajax, ['pings' => $pings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PingRequest $request)
    {
        if (!Helper::isOwner($request->all())) return response()->json(['result' => 'error'], 403);

        $ping = $this->pingRepo->create($request->all());
        return response()->data($this->ajax, ['ping' => $ping]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sessionId)
    {
        $ping = $this->pingRepo->getBySessionId($sessionId);
        
        if (!$ping) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($ping)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['ping' => $ping]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
