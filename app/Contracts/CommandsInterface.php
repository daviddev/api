<?php 

namespace App\Contracts;

interface CommandsInterface
{
	/**
    * Create command
    *
    * @return command
    */
    public function create($data);

    /**
    * Get commands.
    *
    * @return  $commands
    */
    public function getAll();

    /**
    * Get command by Id.
    *
    * @param integer $id
    * @return  $command
    */
    public function getOne($id);

    /**
    * Get unprocessed command by stationId since $start.
    *
    * @param integer $stationId
    * @param integer $start
    * @return  $command
    */
    public function getByStationId($stationId, $start);

    /**
    * Update command by id
    *
    * @return command
    */
    public function update($id, $data);

    /**
    * Delete command by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id);
}