<?php 

namespace App\Contracts;

interface LocationsInterface
{
    /**
    * Get location by pos_id.
    *
    * @param integer $posId
    * @return  $location
    */
    public function getByPosId($posId);
    
    /**
    * Get all locations
    *
    * @return locations
    */
    public function getAllLocations();

    /**
    * Get all locations with relations
    *
    * @return locations
    */
    public function getAllLocationsWithRels();

	/**
    * Get user's locations
    *
    * @return locations
    */
	public function getUserLocations($userId);

    /**
    * Get user's locations with relations
    *
    * @return locations
    */
    public function getUserLocationsWithRels($userId);

	/**
    * Create location
    *
    * @return location
    */
	public function create($data);

    /**
    * Get location by id
    *
    * @return location
    */
    public function getOne($id);

    /**
    * Update location by id
    *
    * @return location
    */
    public function update($id, $data);

    /**
    * Delete location by id
    *
    * @return location
    */
    public function delete($id);
}