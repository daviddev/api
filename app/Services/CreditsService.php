<?php 

namespace App\Services;

use App\Contracts\CreditsInterface;
use App\Credit;

class CreditsService implements CreditsInterface
{
	/**
	* Create a new instance of CreditsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->credit = new Credit();
	}

	/**
    * Create credit
    *
    * @return credit
    */
	public function create($data)
	{
		return $this->credit->create($data);
	}

	/**
    * Get credit by order id
    *
    * @return credit
    */
	public function getByOrderId($orderId)
	{
		return $this->credit->where('order_id', $orderId)->first();
	}

	/**
    * Get all credits
    *
    * @return credits
    */
	public function getAllCredits()
	{
		return $this->credit->with(['customer'])->get();
	}

	/**
    * Get credits by user id
    *
    * @return credits
    */
	public function getUserCredits($userId)
	{
		return $this->credit
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->with(['customer'])->get();
	}

	/**
    * Get credits by location id
    *
    * @return credits
    */
	public function getLocationCredits($locationId)
	{
		return $this->credit
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})->with(['customer'])->get();
	}

	/**
	* Get credit by Id.
	*
	* @param integer $id
	* @return  $credit
	*/
	public function getOne($id)
	{
		return $this->credit->find($id);
	}

	/**
	* Get credits.
	*
	* @return  $credits
	*/
	public function getAll()
	{
		return $this->credit->get();
	}

	/**
    * Update credit by id
    *
    * @return credit
    */
	public function update($id, $data)
	{
		return $this->credit->where('id', $id)->update($data);
	}

	/**
    * Delete credit by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->credit->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}