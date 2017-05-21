<?php 

namespace App\Services;

use App\Contracts\LocationsInterface;
use App\Location;

use Carbon\Carbon;

class LocationsService implements LocationsInterface
{
	/**
	* Create a new instance of LocationsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->location = new Location();
	}

	/**
    * Get all locations
    *
    * @return locations
    */
	public function getAllLocations()
	{
		return $this->location->get();
	}

	/**
	* Get location by pos_id.
	*
	* @param integer $posId
	* @return  $location
	*/
	public function getByPosId($posId)
	{
		return $this->location->where('pos_id', $posId)->first();
	}

	/**
    * Get all locations with relations
    *
    * @return locations
    */
	public function getAllLocationsWithRels()
	{
		return $this->location->with(['company', 'credits', 'customers', 'games', 'sessions', 'sessions.customer', 'sessions.game'])->get();

	}

	/**
    * Get user's locations
    *
    * @return locations
    */
	public function getUserLocations($userId)
	{
		return $this->location
					->whereHas('company', function ($query) use ($userId) {
						$query->whereHas('user', function($q) use ($userId) {
							$q->where('users.id', $userId);
						});
					})->get();

	}

	/**
    * Get user's locations with relations
    *
    * @return locations
    */
	public function getUserLocationsWithRels($userId)
	{
		return $this->location
					->whereHas('company', function ($query) use ($userId) {
						$query->whereHas('user', function($q) use ($userId) {
							$q->where('users.id', $userId);
						});
					})->with(['company', 'credits', 'customers', 'games', 'sessionsToday', 'sessionsLastMonth', 'sessions', 'sessions.customer', 'sessions.game'])->get();

	}

	/**
    * Create location
    *
    * @return location
    */
	public function create($data)
	{
		return $this->location->create($data);
	}

	/**
    * Get location by id
    *
    * @return location
    */
	public function getOne($id)
	{
		return $this->location->find($id);
	}

	/**
    * Update location by id
    *
    * @return location
    */
	public function update($id, $data)
	{
		return $this->location->where('id', $id)->update($data);
	}

	/**
    * Delete location by id
    *
    * @return location
    */
	public function delete($id)
	{
		return $this->location->where('id', $id)->update(['deleted_at' => Carbon::now()]);
	}
}