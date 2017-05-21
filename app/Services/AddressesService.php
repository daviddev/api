<?php 

namespace App\Services;

use App\Contracts\AddressesInterface;
use App\Address;

class AddressesService implements AddressesInterface
{
	/**
	* Create a new instance of AddressesService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->address = new Address();
	}

	/**
    * Create address
    *
    * @return address
    */
	public function create($data)
	{
		return $this->address->create($data);
	}

	/**
	* Get address by phone or address1.
	*
	* @param integer $phone
	* @param string $address
	* @return  $address
	*/
	public function getByPhoneOrAddress($phone, $address)
	{
		return $this->address->where('phone', $phone)->orWhere('address1', $address)->first();
	}

	/**
	* Get address by Id.
	*
	* @param integer $id
	* @return  $address
	*/
	public function getOne($id)
	{
		return $this->address->find($id);
	}

	/**
	* Get addresses.
	*
	* @return  $addresses
	*/
	public function getAll()
	{
		return $this->address->get();
	}

	/**
	* Get addresses by user id
	*
	* @return  $addresses
	*/
	public function getUserAddresses($userId)
	{
		return $this->address->whereHas('customer', function($customerQuery) use ($userId) {
						$customerQuery->whereHas('location', function($locationQuery) use ($userId) {
							$locationQuery->whereHas('company', function ($query) use ($userId) {
								$query->whereHas('user', function($q) use ($userId) {
									$q->where('users.id', $userId);
								});
							});
						});
					})->get();
	}

	/**
	* Get addresses by location id
	*
	* @return  $addresses
	*/
	public function getLocationAddresses($locationId)
	{
		return $this->address->whereHas('customer', function($customerQuery) use ($locationId) {
						$customerQuery->whereHas('location', function($query) use ($locationId) {
							$query->whereId($locationId);
						});
					})->get();
	}

	/**
	* Update address.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateAddress
	*/
	public function update($id, $dataArray)
	{
		return $this->address->find($id)->update($dataArray);
	}

	/**
    * Delete address by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->address->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}