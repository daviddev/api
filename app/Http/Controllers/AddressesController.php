<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\AddressesInterface;

class AddressesController extends BaseController
{
    public function __construct(Request $request,
                                AddressesInterface $addressRepo)
    {
        parent::__construct($request);
        $this->addressRepo = $addressRepo;
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
            $addresses = $this->addressRepo->getAll();
        } elseif ($user->role == 'admin') {
            $addresses = $this->addressRepo->getUserAddresses($user->id);
        } else {
            $addresses = $this->addressRepo->getLocationAddresses($user->location_id);
        }

        return response()->data($this->ajax, ['addresses' => $addresses]);
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
        $address = $this->addressRepo->create($request->all());
        return response()->data($this->ajax, ['address' => $address]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = $this->addressRepo->getOne($id);
        if (!$address) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($address->customer)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['address' => $address]);
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
        $address = $this->addressRepo->getOne($id);
        if (!$address) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($address->customer)) return response()->json(['result' => 'error'], 403);

        $this->addressRepo->update($id, $request->all());
        $address = $this->addressRepo->getOne($id);
        return response()->data($this->ajax, ['address' => $address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = $this->addressRepo->getOne($id);
        if (!$address) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($address->customer)) return response()->json(['result' => 'error'], 403);
        
        $this->addressRepo->delete($id);
        return response()->data($this->ajax, ['result' => 'success']);
    }
}
