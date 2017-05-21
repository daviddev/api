<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use DB;
use Carbon\Carbon;

### Helpers ###
use App\Helper;

### Requests ###
use App\Http\Requests\CustomerRequest;

### Contracts ###
use App\Contracts\CustomersInterface;
use App\Contracts\AddressesInterface;
use App\Contracts\CreditsInterface;
use App\Contracts\StationsInterface;
use App\Contracts\GamesInterface;
use App\Contracts\CommandsInterface;
use App\Contracts\VendWebhooksInterface;

class CustomersController extends BaseController
{
    public function __construct(Request $request,
                                AddressesInterface $addressRepo,
                                CustomersInterface $customerRepo,
                                CreditsInterface $creditRepo,
                                StationsInterface $stationRepo,
                                GamesInterface $gameRepo,
                                CommandsInterface $commandRepo,
                                VendWebhooksInterface $vendWebhookRepo)
    {
        parent::__construct($request);
        $this->customerRepo = $customerRepo;
        $this->addressRepo = $addressRepo;
        $this->creditRepo = $creditRepo;
        $this->stationRepo = $stationRepo;
        $this->gameRepo = $gameRepo;
        $this->commandRepo = $commandRepo;
        $this->vendWebhookRepo = $vendWebhookRepo;
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
            $customers = $this->customerRepo->getAllCustomers($this->ajax);
        elseif ($user->role == 'admin')
            $customers = $this->customerRepo->getUserCustomers($user->id, $this->ajax);
        else
            $customers = $this->customerRepo->getLocationCustomers($user->location_id, $this->ajax);

        foreach ($customers as $key => $customer) {
            $totalPurchased = 0;
            $totalUsed = 0;

            foreach ($customer->credits()->get() as $creditKey => $credit) {
                $totalPurchased += $credit->duration;
            }
            foreach ($customer->pings()->get() as $pingKey => $ping) {
                $totalUsed += $ping->duration;
            }
            $totalAvailable = $totalPurchased - $totalUsed;

            $customers[$key]->totalPurchased = $totalPurchased;
            $customers[$key]->totalUsed = $totalUsed;
            $customers[$key]->totalAvailable = $totalAvailable;
        }

        return response()->data($this->ajax, ['customers' => $customers]);
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
    public function store(CustomerRequest $request)
    {
        $address = $request->address;
        $customer = $request->customer;
        $credit = $request->credit;
        $station = $request->station;

        if (!Helper::isOwner($customer)) return response()->json(['result' => 'error'], 403);
        if (!Helper::isOwner($credit)) return response()->json(['result' => 'error'], 403);

        $createdCustomer = DB::transaction(function () use ($address, $customer, $credit, $station) {
            if ($address)
            {
                $createAddress = $this->addressRepo->create($address);
                $customer['address_id'] = $createAddress->id;
            }

            if (isset($customer['date_of_birth']))
                $customer['date_of_birth'] = Carbon::parse($customer['date_of_birth'])->addHour(10);
            $createdCustomer = $this->customerRepo->createOne($customer);

            $credit['customer_id'] = $createdCustomer->id;
            if ($credit['duration']=='other') $credit['duration'] = $credit['duration_other'];
            $credit['duration'] = intval($credit['duration']) * 60;
            $credit['purchase_datetime'] = Carbon::parse($credit['purchase_datetime'])->addHour(10);
            $this->creditRepo->create($credit);

            if (isset($station['station_id'])) {
                $this->stationRepo->update($station['station_id'], ['customer_id' => $createdCustomer->id]);

                $command = [
                    'station_id' => $station['station_id'],
                    'type' => 'customerAssignment',
                    'message' => $createdCustomer->id,
                    'processed' => 1
                ];
                $this->commandRepo->create($command);
            }
            return $createdCustomer;
        });

        return response()->data($this->ajax, ['customer' => $createdCustomer]);
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
        $customer = $this->customerRepo->getOne($id);

        if (!$customer) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($customer)) return response()->json(['result' => 'error'], 403);

        $sessionsGroups = $customer->sessions()->where('start_datetime', '>=', Carbon::now()->subYear())->get()->groupBy(function($date) {
                return Carbon::parse($date->start_datetime)->format('M');
            });

        $countByMonths = [];
        $i = 11;
        while ($i >= 0) {
            $countByMonths[Carbon::now()->subMonths($i)->format('M')] = 0;
            $i--;
        }

        foreach ($sessionsGroups as $key => $group) {
            $countByMonths[$key] = count($group);
        }

        $totalPurchased = 0;
        $totalUsed = 0;

        foreach ($customer->credits()->get() as $creditKey => $credit) {
            $totalPurchased += $credit->duration;
        }
        foreach ($customer->pings()->get() as $pingKey => $ping) {
            $totalUsed += $ping->duration;
        }
        $totalAvailable = $totalPurchased - $totalUsed;

        $customer->totalPurchased = $totalPurchased;
        $customer->totalUsed = $totalUsed;
        $customer->totalAvailable = $totalAvailable;

        $customer->lastSession = $customer->sessions->sortByDesc('created_at')->first();
        $customer->lastSessions = $customer->sessions->where('created_at', '>', Carbon::now()->subMonths(6));
        $groupedSessions = $customer->sessions->groupBy('game_id');

        $gamesDurations = [];
        foreach ($groupedSessions as $gameId => $gameSessions) {
            $duration = 0;
            foreach ($gameSessions as $session) {
                $duration += $session->duration;
            }
            $gamesDurations[$gameId] = $duration;
        }

        $favoriteGame = [];
        if ($gamesDurations)
        {
            $gameId = array_search(max($gamesDurations), $gamesDurations);
            $favoriteGame = $this->gameRepo->getOne($gameId);
            if ($favoriteGame)
                $favoriteGame->duration = $gamesDurations[$gameId];
            else
                $favoriteGame = [];
        }

        $dataResponse = [
            'customer' => $customer,
            'favoriteGame' => $favoriteGame,
            'countByMonths' => $countByMonths
        ];
        return response()->data($this->ajax, $dataResponse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = $this->customerRepo->getCustomerEdit($id);
        if (!$customer) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($customer)) return response()->json(['result' => 'error'], 403);

        return response()->data($this->ajax, ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @param  int  $id
     * @return json
     */
    public function update(CustomerRequest $request, $id)
    {
        $customer = $this->customerRepo->getCustomerEdit($id);
        if (!$customer) return response()->json(['result' => 'error'], 403);

        if (!Helper::isOwner($customer)) return response()->json(['result' => 'error'], 403);

        $address = $request->address;
        $customer = $request->customer;

        DB::transaction(function () use ($address, $customer, $id) {
            if ($address)
            {
                if (isset($address['id'])) {
                    $this->addressRepo->update($address['id'], $address);
                } else {
                    $createAddress = $this->addressRepo->create($address);
                    $customer['address_id'] = $createAddress->id;
                }
            }

            if (isset($customer['date_of_birth']))
                $customer['date_of_birth'] = Carbon::parse($customer['date_of_birth'])->addHour(10);
            $updateCustomer = $this->customerRepo->update($id, $customer);
        });

        $customer = $this->customerRepo->getCustomerEdit($id);
        return response()->data($this->ajax, ['customer' => $customer]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customerRepo->delete($id);

        return response()->data($this->ajax, ['result' => 'success']);
    }

    /**
     * Customer vend.
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

        $addressData = [
            'address1' => $data->contact->physical_address1,
            'address2' => $data->contact->physical_address2,
            'company' => $data->contact->company_name,
            'city' => $data->contact->physical_city,
            'province' => $data->contact->physical_state,
            'country' => $data->contact->physical_country_id,
            'zip' => $data->contact->physical_postcode,
            'phone' => $data->contact->phone
        ];

        $customerData = [
            'location_id' => \App\Location::first()->id,
            'email' => $data->contact->email,
            'accepts_marketing' => false,
            'first_name' => $data->contact->first_name,
            'last_name' => $data->contact->last_name,
            'state' => 'enabled',
            'verified_email' => false,
            'tax_exempt' => false,
            'tags' => '',
            'pos_id' => $data->id,
            'sex' => $data->sex,
            'date_of_birth' => $data->date_of_birth
        ];

        $customer = $this->customerRepo->getByEmailOrPos($data->contact->email, $data->id);
        if (!$customer) {
            $createdAddress = $this->addressRepo->create($addressData);
            $customerData['address_id'] = $createdAddress->id;
            $this->customerRepo->createOne($customerData);
        } else {
            $addr = $this->addressRepo->getByPhoneOrAddress($data->contact->phone, $data->contact->physical_address1);

            if ($addr) {
                $this->addressRepo->update($addr->id, $addressData);
                $customerData['address_id'] = $addr->id;
            } else {
                $createdAddress = $this->addressRepo->create($addressData);
                $customerData['address_id'] = $createdAddress->id;
            }
            $this->customerRepo->update($customer->id, $customerData);
        }
        return response()->json('success', 204);
    }
}



















