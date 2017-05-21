<?php 

namespace App\Contracts;

interface PingsInterface
{
	/**
    * Create ping
    *
    * @return ping
    */
    public function create($data);

    /**
    * Get pings by sessionId.
    *
    * @param integer $sessionId
    * @return  $pings
    */
    public function getBySessionId($sessionId);
    
    /**
    * Get pinges.
    *
    * @return  $pinges
    */
    public function getAll();

    /**
	* Update ping.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateping
	*/
	public function update($id, $dataArray);

	/**
    * Delete ping by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id);
}