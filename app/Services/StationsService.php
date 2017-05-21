<?php 

namespace App\Services;

use App\Contracts\StationsInterface;
use App\Station;

class StationsService implements StationsInterface
{
	/**
	* Create a new instance of StationsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->station = new Station();
	}

	/**
	* Create Station.
	*
	* @param array $dataArray
	* @return  station
	*/
	public function createOne($dataArray)
	{
		return $this->station->create($dataArray);
	}

	/**
    * Get all stations
    *
    * @return stations
    */
	public function getAllStations($userId)
	{
		return $this->station->with(['location', 'customer'])->get();
	}

	/**
    * Get all available stations
    *
    * @return stations
    */
	public function getAllAvailableStations($userId)
	{
		return $this->station
					->where('customer_id', null)
					->with('location')->get();
	}

	/**
    * Get user's stations
    *
    * @return stations
    */
	public function getUserStations($userId)
	{
		return $this->station
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->with(['location', 'customer'])->get();
	}

	/**
    * Get user's available stations
    *
    * @return stations
    */
	public function getUserAvailableStations($userId)
	{
		return $this->station
					->where('customer_id', null)
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->with('location')->get();
	}

	/**
    * Get stations by location id
    *
    * @return stations
    */
	public function getLocationStations($locationId)
	{
		return $this->station
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})->with(['location', 'customer'])->get();
	}

	/**
    * Get available stations by location id
    *
    * @return stations
    */
	public function getLocationAvailableStations($locationId)
	{
		return $this->station
					->where('customer_id', null)
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})->with('location')->get();
	}

	/**
    * Update stations
    *
    * @return station
    */
	public function update($id, $data)
	{
		return $this->station->find($id)->update($data);
	}

	/**
    * Get station by id
    *
    * @return station
    */
	public function getOne($id)
	{
		return $this->station->find($id);
	}

	/**
    * Get station by id
    *
    * @return station
    */
	public function getOneWithRel($id)
	{
		return $this->station->with(['location', 'customer', 'sessions'])->find($id);
	}

	/**
    * Delete station by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->station->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}