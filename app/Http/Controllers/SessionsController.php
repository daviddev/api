<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

### Requests ###
use App\Http\Requests\SessionRequest;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\SessionsInterface;

class SessionsController extends BaseController
{
    public function __construct(Request $request,
                                SessionsInterface $sessionRepo)
    {
        parent::__construct($request);
        $this->sessionRepo = $sessionRepo;
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
            if ($this->ajax) {
                $activeSessions = $this->sessionRepo->getAllActiveSessions();
                $historySessions = $this->sessionRepo->getAllHistorySessions();
            } else {
                $sessions = $this->sessionRepo->getAllSessions();
            }
        } elseif ($user->role == 'admin') {
            if ($this->ajax) {
                $activeSessions = $this->sessionRepo->getUserActiveSessions($user->id);
                $historySessions = $this->sessionRepo->getUserHistorySessions($user->id);
            } else {
                $sessions = $this->sessionRepo->getUserSessions($user->id);
            }
        } else {
            if ($this->ajax) {
                $activeSessions = $this->sessionRepo->getLocationActiveSessions($user->location_id);
                $historySessions = $this->sessionRepo->getLocationHistorySessions($user->location_id);
            } else {
                $sessions = $this->sessionRepo->getLocationSessions($user->location_id);
            }  
        }
        
        if ($this->ajax) {
            $dataResponse = [
                'activeSessions' => $activeSessions,
                'historySessions' => $historySessions
            ];
            return response()->json($dataResponse);
        } else {
            return response()->json($sessions);
        }
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
     * @param  \App\Http\Requests\SessionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SessionRequest $request)
    {
        if (!Helper::isOwner($request->all())) return response()->json(['result' => 'error'], 403);

        $session = $this->sessionRepo->create($request->all());
        return response()->data($this->ajax, ['session' => $session]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $session = $this->sessionRepo->getOne($id);

        if (!$session) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($session)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['session' => $session]);
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
        $session = $this->sessionRepo->getOne($id);
        if (!$session) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($session)) return response()->json(['result' => 'error'], 403);

        $this->sessionRepo->update($id, $request->all());
        $session = $this->sessionRepo->getOne($id);
        return response()->data($this->ajax, ['session' => $session]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $session = $this->sessionRepo->getOne($id);
        if (!$session) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($session)) return response()->json(['result' => 'error'], 403);

        $this->sessionRepo->delete($id);
        return response()->data($this->ajax, ['result' => 'success']);
    }
}
