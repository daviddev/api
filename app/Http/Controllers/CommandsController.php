<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\CommandsInterface;
use App\Contracts\StationsInterface;

class CommandsController extends BaseController
{
    public function __construct(Request $request,
                                CommandsInterface $commandRepo,
                                StationsInterface $stationRepo)
    {
        parent::__construct($request);
        $this->commandRepo = $commandRepo;
        $this->stationRepo = $stationRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        if ($user->role == 'super-admin') {
            $commands = $this->commandRepo->getAll();
        } elseif ($user->role == 'admin') {
            $commands = $this->commandRepo->getUserCommands($user->id);
        } else {
            $commands = $this->commandRepo->getLocationCommands($user->location_id);
        }

        return response()->data($this->ajax, ['commands' => $commands]);
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
    public function store(Request $request)
    {
        $command = $this->commandRepo->create($request->all());
        return response()->data($this->ajax, ['command' => $command]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command = $this->commandRepo->getOne($id);
        if (!$command) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($command->station)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['command' => $command]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $command = $this->commandRepo->getOne($id);
        if (!$command) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($command->station)) return response()->json(['result' => 'error'], 403);

        $this->commandRepo->update($id, $request->all());
        $command = $this->commandRepo->getOne($id);
        return response()->data($this->ajax, ['command' => $command]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = $this->commandRepo->getOne($id);
        if (!$command) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($command->station)) return response()->json(['result' => 'error'], 403);

        $this->commandRepo->delete($id);
        return response()->data($this->ajax, ['result' => 'success']);
    }

    /**
     * Display the specified resources.
     *
     * @param  int  $stationId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showByStation($stationId, Request $request)
    {
        $station = $this->stationRepo->getOne($stationId);

        if (!Helper::isOwner($station)) return response()->json(['result' => 'error'], 403);

        $commands = $this->commandRepo->getByStationId($stationId, $request->start);
        return response()->data($this->ajax, ['commands' => $commands]);
    }
}
