<?php 

namespace App\Services;

use App\Contracts\PingsInterface;
use App\Ping;

class PingsService implements PingsInterface
{
	/**
	* Create a new instance of PingsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->ping = new Ping();
	}

	/**
    * Create ping
    *
    * @return ping
    */
	public function create($data)
	{
		return $this->ping->create($data);
	}

	/**
	* Get pings by sessionId.
	*
	* @param integer $sessionId
	* @return  $pings
	*/
	public function getBySessionId($sessionId)
	{
		return $this->ping->where('session_id', $sessionId)->get();
	}

	/**
	* Get pinges.
	*
	* @return  $pinges
	*/
	public function getAll()
	{
		return $this->ping->get();
	}

	/**
	* Get pinges.
	*
	* @return  $pinges
	*/
	public function getUserPings($userId)
	{
		return $this->ping->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->get();
	}

	/**
	* Get pinges.
	*
	* @return  $pinges
	*/
	public function getLocationPings($locationId)
	{
		return $this->ping->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})->get();
	}

	/**
	* Update ping.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateping
	*/
	public function update($id, $dataArray)
	{
		return $this->ping->find($id)->update($dataArray);
	}

	/**
    * Delete ping by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->ping->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}