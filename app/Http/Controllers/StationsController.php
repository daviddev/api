<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;

### Helpers ###
use App\Helper;

### Requests ###
use App\Http\Requests\StationRequest;

### Contracts ###
use App\Contracts\StationsInterface;
use App\Contracts\CommandsInterface;

class StationsController extends BaseController
{
    public function __construct(Request $request,
                                StationsInterface $stationRepo,
                                CommandsInterface $commandRepo)
    {
        parent::__construct($request);
        $this->stationRepo = $stationRepo;
        $this->commandRepo = $commandRepo;
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
            if ($request->action == 'available')
                $stations = $this->stationRepo->getAllAvailableStations($user->id);
            else
                $stations = $this->stationRepo->getAllStations($user->id);
        } elseif ($user->role == 'admin') {
            if ($request->action == 'available')
                $stations = $this->stationRepo->getUserAvailableStations($user->id);
            else
                $stations = $this->stationRepo->getUserStations($user->id);
        } else {
            if ($request->action == 'available')
                $stations = $this->stationRepo->getLocationAvailableStations($user->location_id);
            else
                $stations = $this->stationRepo->getLocationStations($user->location_id);
        }
        
        return response()->data($this->ajax, ['stations' => $stations]);
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
    public function store(StationRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();
        if($user->role == 'employee') return response()->json(['result' => 'error'], 403);
        
        $this->stationRepo->createOne($request->all());
        return response()->data($this->ajax, ['result' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $station = $this->stationRepo->getOne($id);
        if (!$station) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($station)) return response()->json(['result' => 'error'], 403);

        if ($request->action == 'unassign')
            $this->stationRepo->update($id, ['customer_id' => null]);

        $station = $this->stationRepo->getOneWithRel($id);
        return response()->data($this->ajax, ['station' => $station]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $station = $this->stationRepo->getOne($id);
        if (!$station) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($station)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['station' => $station]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StationRequest $request, $id)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $station = $this->stationRepo->getOne($id);
        if (!$station) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($station)) return response()->json(['result' => 'error'], 403);

        $data = $request->all();
        
        $this->stationRepo->update($id, $data);
        if (isset($request->assign))
        {
            $command = [
                'station_id' => $id,
                'type' => 'customerAssignment',
                'message' => $data['customer_id']
            ];
            $this->commandRepo->create($command);
        }

        $station = $this->stationRepo->getOneWithRel($id);
        return response()->data($this->ajax, ['station' => $station]);
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
        if($user->role == 'employee') return response()->json(['result' => 'error'], 403);

        $station = $this->stationRepo->getOne($id);
        if (!$station) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($station)) return response()->json(['result' => 'error'], 403);

        $this->stationRepo->delete($id);

        return response()->data($this->ajax, ['result' => 'success']);
    }
}
