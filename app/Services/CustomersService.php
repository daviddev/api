<?php 

namespace App\Services;

use App\Contracts\CustomersInterface;
use App\Customer;

class CustomersService implements CustomersInterface
{
	/**
	* Create a new instance of CustomersService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->customer = new Customer();
	}

	/**
	* Get Customer by email or pos_id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getByEmailOrPos($email, $posId)
	{
		return $this->customer->where('email', $email)->orWhere('pos_id', $posId)->first();
	}

	/**
	* Get Customer by pos_id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getByPosId($posId)
	{
		return $this->customer->where('pos_id', $posId)->first();
	}

	/**
	* Get Customer by Id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getOne($id)
	{
		return $this->customer->with(['location.company', 'sessions.game', 'credits'])->find($id);
	}

	/**
	* Get Customer by Id for edit.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getCustomerEdit($id)
	{
		return $this->customer->with('address')->find($id);
	}

	/**
	* Create Customer.
	*
	* @param array $dataArray
	* @return  $customer
	*/
	public function createOne($dataArray)
	{
		return $this->customer->create($dataArray);
	}

	/**
    * Get all customers
    *
    * @return customers
    */
	public function getAll()
	{
		return $this->customer->get();
	}

	/**
    * Get all customers
    *
    * @return customers
    */
	public function getAllCustomers($isAjax)
	{
		$customers = $this->customer;

		if ($isAjax)
			$customers = $customers->with(['location', 'sessions', 'sessions.game', 'credits']);

		return $customers->get();
	}

	/**
    * Get customers by user id
    *
    * @return customers
    */
	public function getUserCustomers($userId, $isAjax)
	{
		$customers = $this->customer
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					});

		if ($isAjax)
			$customers = $customers->with(['location', 'sessions', 'sessions.game', 'credits']);

		return $customers->get();
	}

	/**
    * Get customers by location id
    *
    * @return customers
    */
	public function getLocationCustomers($locationId, $isAjax)
	{
		$customers = $this->customer
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					});

		if ($isAjax)
			$customers = $customers->with(['location', 'sessions', 'sessions.game', 'credits']);

		return $customers->get();
	}

	/**
	* Update customer.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateJob
	*/
	public function update($id, $dataArray)
	{
		return $this->customer->find($id)->update($dataArray);
	}

	/**
    * Delete customer by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->customer->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}