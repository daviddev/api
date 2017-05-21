<?php

namespace App;

### Services ###
use App\Services\LocationsService;

class Helper {

	/**
	 * Check owner
	 *
	 * @return json
	 */
	public static function isOwner($model) {
		$locationRepo = new LocationsService();
		
		$user = \JWTAuth::parseToken()->authenticate();

		if ($user->role == 'employee' && $model['location_id'] != $user->location_id)
            return false;

        if ($user->role == 'admin'){
            $locations = $locationRepo->getUserLocations($user->id);
            $isOwner = false;
            foreach ($locations as $location) {
                if ($location->id == $model['location_id'])
                    $isOwner = true;
            }
            if (!$isOwner)
                return false;
        }
        return true;
	}

    /**
     * Check owner of location
     *
     * @return json
     */
    public static function isOwnerOfLocation($locationId) {
        $locationRepo = new LocationsService();
        
        $user = \JWTAuth::parseToken()->authenticate();

        if ($user->role == 'admin'){
            $locations = $locationRepo->getUserLocations($user->id);
            $isOwner = false;
            foreach ($locations as $location) {
                if ($location->id == $locationId)
                    $isOwner = true;
            }
            if (!$isOwner)
                return false;
        }
        return true;
    }
}