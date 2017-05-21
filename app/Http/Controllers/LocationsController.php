<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

### Helpers ###
use App\Helper;

### Requests ###
use App\Http\Requests\LocationRequest;

### Contracts ###
use App\Contracts\LocationsInterface;

class LocationsController extends BaseController
{
    public function __construct(Request $request,
                                LocationsInterface $locationRepo)
    {
        parent::__construct($request);
        $this->locationRepo = $locationRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        if ($user->role == 'super-admin') {
            if ($request->dashboard)
                $locations = $this->locationRepo->getAllLocationsWithRels();
            else
                $locations = $this->locationRepo->getAllLocations();
        } elseif ($user->role == 'admin') {
            if ($request->dashboard)
                $locations = $this->locationRepo->getUserLocationsWithRels($user->id);
            else
                $locations = $this->locationRepo->getUserLocations($user->id);
        } else {
            if ($request->dashboard)
                $locations = $user->location()->with(['company', 'credits', 'customers', 'games', 'sessions', 'sessions.customer', 'sessions.game'])->get();
            else
                $locations = $user->location;
        }

        return response()->data($this->ajax, ['locations' => $locations]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        if ($user->role == 'employee') return response()->json(['result' => 'error'], 403);

        $locationData = $request->all();
        // $locationData['company_id'] = $user->company->first()->id;
        $nameArray = explode(' ', $user->name);
        $locationData['first_name'] = $nameArray[0];
        $locationData['last_name'] = $nameArray[1];
        $location = $this->locationRepo->create($locationData);

        return response()->data($this->ajax, ['location' => $location]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \JWTAuth::parseToken()->authenticate();
        if ($user->role == 'employee') return response()->json(['result' => 'error'], 403);

        $location = $this->locationRepo->getOne($id);

        if (!Helper::isOwnerOfLocation($location->id)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['location' => $location]);
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
        $user = \JWTAuth::parseToken()->authenticate();
        if ($user->role == 'employee') return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwnerOfLocation($id)) return response()->json(['result' => 'error'], 403);

        $this->locationRepo->update($id, $request->all());
        $location = $this->locationRepo->getOne($id);

        return response()->data($this->ajax, ['location' => $location]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \JWTAuth::parseToken()->authenticate();
        if ($user->role == 'employee') return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwnerOfLocation($id)) return response()->json(['result' => 'error'], 403);

        $this->locationRepo->delete($id);

        return response()->data($this->ajax, ['result' => 'success']);
    }
}
