<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

### Contracts ###
use App\Contracts\UsersInterface;

use JWTAuth;
use Auth;

class UsersController extends BaseController
{
    public function __construct(Request $request,
                                UsersInterface $userRepo)
    {
        parent::__construct($request);
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepo->getall();
        return response()->json($users);
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
        if (Auth::user()->role != 'super-admin') return response()->json(['result' => 'error'], 403);
        $this->userRepo->createOne($request->all());
        $users = $this->userRepo->getall();
        return response()->data($this->ajax, ['result' => 'success', 'users' => $users]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if (Auth::user()->role != 'super-admin') return response()->json(['result' => 'error'], 403);
        $this->userRepo->update($id, $request->all());
        $users = $this->userRepo->getall();
        return response()->data($this->ajax, ['users' => $users]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role != 'super-admin') return response()->json(['result' => 'error'], 403);
        $user = $this->userRepo->getEdit($id);
        if (!$user) return response()->json(['result' => 'error'], 403);

        $this->userRepo->delete($id);
        return response()->data($this->ajax, ['result' => 'success']);
    }

    public function authUser()
    {
        $user = \JWTAuth::parseToken()->authenticate();
        
        return response()->json(['user' => $user]);
    }
}
