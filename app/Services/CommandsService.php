<?php 

namespace App\Services;

use App\Contracts\CommandsInterface;
use App\Command;

class CommandsService implements CommandsInterface
{
	/**
	* Create a new instance of CommandsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->command = new Command();
	}

	/**
    * Create command
    *
    * @return command
    */
	public function create($data)
	{
		return $this->command->create($data);
	}

	/**
	* Get command by Id.
	*
	* @param integer $id
	* @return  $command
	*/
	public function getOne($id)
	{
		return $this->command->find($id);
	}

	/**
	* Get unprocessed command by stationId since $start.
	*
	* @param integer $stationId
	* @param integer $start
	* @return  $command
	*/
	public function getByStationId($stationId, $start)
	{
		return $this->command->where('station_id', $stationId)
							 ->where('processed', 0)
							 ->where('id', '>', $start!=null ? $start : 0)
							 ->get();
	}

	/**
	* Get commands.
	*
	* @return  $commands
	*/
	public function getAll()
	{
		return $this->command->get();
	}

	/**
	* Get commands by user id.
	*
	* @param integer $userId
	* @return  $commands
	*/
	public function getUserCommands($userId)
	{
		return $this->command->whereHas('station', function($stationQuery) use ($userId) {
						$stationQuery->whereHas('location', function($locationQuery) use ($userId) {
							$locationQuery->whereHas('company', function ($query) use ($userId) {
								$query->whereHas('user', function($q) use ($userId) {
									$q->where('users.id', $userId);
								});
							});
						});
					})->get();
	}

	/**
	* Get commands by location id.
	*
	* @param integer $locationId
	* @return  $commands
	*/
	public function getLocationCommands($locationId)
	{
		return $this->command->whereHas('station', function($stationQuery) use ($locationId) {
						$stationQuery->whereHas('location', function($query) use ($locationId) {
							$query->whereId($locationId);
						});
					})->get();
	}

	/**
    * Update command by id
    *
    * @return command
    */
	public function update($id, $data)
	{
		return $this->command->where('id', $id)->update($data);
	}

	/**
    * Delete command by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->command->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}