<?php 

namespace App\Contracts;

interface SessionsInterface
{
    /**
    * Create Session.
    *
    * @param array $dataArray
    * @return  $session
    */
    public function create($dataArray);

    /**
    * Get Session by Id.
    *
    * @param integer $id
    * @return  $session
    */
    public function getOne($id);

    /**
    * Get all active sessions
    *
    * @return sessions
    */
    public function getAllActiveSessions();
    
    /**
    * Get all history sessions
    *
    * @return sessions
    */
    public function getAllHistorySessions();

    /**
    * Get all sessions
    *
    * @return sessions
    */
    public function getAllSessions();

	/**
    * Get user's active sessions
    *
    * @return sessions
    */
	public function getUserActiveSessions($userId);

	/**
    * Get user's history sessions
    *
    * @return sessions
    */
	public function getUserHistorySessions($userId);

    /**
    * Get user's sessions
    *
    * @return sessions
    */
    public function getUserSessions($userId);

    /**
    * Get active sessions by locetion id
    *
    * @return sessions
    */
    public function getLocationActiveSessions($locationId);

    /**
    * Get user's history sessions
    *
    * @return sessions
    */
    public function getLocationHistorySessions($locationId);

    /**
    * Get sessions by locetion id
    *
    * @return sessions
    */
    public function getLocationSessions($locationId);

    /**
    * Update session by id
    *
    * @return session
    */
    public function update($id, $data);

    /**
    * Delete session by id
    *
    * @param integer $id
    * @return delete
    */
    public function delete($id);
}