<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;

use \Carbon\Carbon;

### Requests ###
use App\Http\Requests\GameRequest;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\GamesInterface;

class GamesController extends BaseController
{
    public function __construct(Request $request,
                                GamesInterface $gameRepo)
    {
        parent::__construct($request);
        $this->gameRepo = $gameRepo;
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
            $games = $this->gameRepo->getAllGames($user->id);
        elseif ($user->role == 'admin')
            $games = $this->gameRepo->getUserGames($user->id);
        else
            $games = $this->gameRepo->getLocationGames($user->location_id);
        
        return response()->data($this->ajax, ['games' => $games]);
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
     * @param  \App\Http\Requests\GameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameRequest $request)
    {
        $game = $request->all();
        if (!Helper::isOwner($game)) return response()->json(['result' => 'error'], 403);
        
        if (isset($game['release_date']))
            $game['release_date'] = Carbon::parse($game['release_date'])->addHour(10);
        $this->gameRepo->createOne($game);
        return response()->data($this->ajax, ['result' => 'success']);
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

        $game = $this->gameRepo->getOne($id);
        if (!$game) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($game)) return response()->json(['result' => 'error'], 403);
        
        return response()->data($this->ajax, ['game' => $game]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = $this->gameRepo->getGameEdit($id);
        if (!$game) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($game)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['game' => $game]);
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
        $game = $this->gameRepo->getGameEdit($id);
        if (!$game) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($game)) return response()->json(['result' => 'error'], 403);

        $game = $request->all();
        $game['release_date'] = Carbon::parse($game['release_date'])->addHour(10);
        $this->gameRepo->update($id, $game);
        $game = $this->gameRepo->getGameEdit($id);
        return response()->data($this->ajax, ['game' => $game]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = $this->gameRepo->getGameEdit($id);
        if (!$game) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($game)) return response()->json(['result' => 'error'], 403);

        $this->gameRepo->delete($id);

        return response()->data($this->ajax, ['result' => 'success']);
    }
}
