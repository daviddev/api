<?php 

namespace App\Contracts;

interface StationsInterface
{
    /**
    * Create Station.
    *
    * @param array $dataArray
    * @return  station
    */
    public function createOne($dataArray);

    /**
    * Get all stations
    *
    * @return stations
    */
    public function getAllStations($userId);

    /**
    * Get all available stations
    *
    * @return stations
    */
    public function getAllAvailableStations($userId);
    
	/**
    * Get user's stations
    *
    * @return stations
    */
	public function getUserStations($userId);

	/**
    * Get user's available stations
    *
    * @return stations
    */
	public function getUserAvailableStations($userId);

    /**
    * Get stations by location id
    *
    * @return stations
    */
    public function getLocationStations($locationId);

    /**
    * Get available stations by location id
    *
    * @return stations
    */
    public function getLocationAvailableStations($locationId);

    /**
    * Update stations
    *
    * @return station
    */
    public function update($id, $data);

    /**
    * Get station by id
    *
    * @return station
    */
    public function getOne($id);

    /**
    * Get station by id
    *
    * @return station
    */
    public function getOneWithRel($id);

    /**
    * Delete station by id
    *
    * @param integer $id
    * @return delete
    */
    public function delete($id);
}