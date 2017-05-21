<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Carbon\Carbon;

### Requests ###
use App\Http\Requests\CreditRequest;

### Helpers ###
use App\Helper;

### Contracts ###
use App\Contracts\CreditsInterface;
use App\Contracts\CustomersInterface;
use App\Contracts\LocationsInterface;
use App\Contracts\VendWebhooksInterface;
use App\Contracts\ProductsInterface;

class CreditsController extends BaseController
{
    public function __construct(Request $request,
                                CreditsInterface $creditRepo,
                                CustomersInterface $customerRepo,
                                LocationsInterface $locationRepo,
                                VendWebhooksInterface $vendWebhookRepo,
                                ProductsInterface $productRepo)
    {
        parent::__construct($request);
        $this->creditRepo = $creditRepo;
        $this->customerRepo = $customerRepo;
        $this->locationRepo = $locationRepo;
        $this->vendWebhookRepo = $vendWebhookRepo;
        $this->productRepo = $productRepo;
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
            $credits = $this->creditRepo->getAllCredits();
        elseif ($user->role == 'admin')
            $credits = $this->creditRepo->getUserCredits($user->id);
        else
            $credits = $this->creditRepo->getLocationCredits($user->location_id);

        foreach ($credits as $key => $credit) {
            $credits[$key]->purchase_datetime = Carbon::parse($credit->purchase_datetime)->format('m/d/Y');
        }

        return response()->data($this->ajax, ['credits' => $credits]);
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
     * @param  \App\Http\Requests\CreditRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreditRequest $request)
    {
        if (!Helper::isOwner($request->all())) return response()->json(['result' => 'error'], 403);

        $credit = $request->all();
        if ($credit['duration']=='other') $credit['duration'] = $credit['duration_other'];
        $credit['duration'] = intval($credit['duration']) * 60;
        $credit['purchase_datetime'] = Carbon::parse($credit['purchase_datetime'])->addHour(10);

        $credit = $this->creditRepo->create($credit);
        return response()->data($this->ajax, ['credit' => $credit]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $credit = $this->creditRepo->getOne($id);
        if (!$credit) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($credit)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['credit' => $credit]);
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
        $credit = $this->creditRepo->getOne($id);
        if (!$credit) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($credit)) return response()->json(['result' => 'error'], 403);

        $this->creditRepo->update($id, $request->all());
        $credit = $this->creditRepo->getOne($id);
        return response()->data($this->ajax, ['credit' => $credit]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $credit = $this->creditRepo->getOne($id);
        if (!$credit) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($credit)) return response()->json(['result' => 'error'], 403);

        $this->creditRepo->delete($id);
        return response()->data($this->ajax, ['result' => 'success']);
    }

    /**
     * Credit vend.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vend(Request $request, $token=null)
    {
        if (!$token || $token != config('vend.authToken'))
            return response()->json('Unauthorized', 401);

        $data = $request->payload;

        $data = stripslashes($data);
        $this->vendWebhookRepo->create(['payload' => $data]);
        
        $data = json_decode($data);

        $credit = $this->creditRepo->getByOrderId($data->id);
        if ($credit) return response()->json(['success'], 204);

        $customer = $this->customerRepo->getByPosId($data->customer->id);
        $location = $this->locationRepo->getByPosId($data->user->outlet_id);

        foreach ($data->register_sale_products as $vendProduct) {
            $rcvrProduct = $this->productRepo->getByPosId($vendProduct->product_id);

            if ($rcvrProduct->credits > 0)
            {
                for ($i = 1; $i <= $vendProduct->quantity; $i++) { 
                    $creditData = [
                        'order_id' => $data->id,
                        'duration' => (!isset($rcvrProduct->credits) || is_null($rcvrProduct->credits)) ? 0 : $rcvrProduct->credits,
                        'customer_id' => $customer ? $customer->id : 0,
                        'source' => (!isset($rcvrProduct->name) || is_null($rcvrProduct->name)) ? "Unknown" : $rcvrProduct->name,
                        'location_id' => $location ? $location->id : 0,
                        'purchase_price' => $vendProduct->price,
                        'purchase_datetime' => new Carbon($data->sale_date),
                        'pos_id' => $vendProduct->id
                    ];
                    $this->creditRepo->create($creditData);
                }
            }
        }
        return response()->json('success', 204);
    }
}
