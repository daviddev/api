<?php 

namespace App\Services;

use App\Contracts\SessionsInterface;
use App\Session;

use Carbon\Carbon;

class SessionsService implements SessionsInterface
{
	/**
	* Create a new instance of SessionsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->session = new Session();
	}

	/**
	* Create Session.
	*
	* @param array $dataArray
	* @return  $session
	*/
	public function create($dataArray)
	{
		return $this->session->create($dataArray);
	}

	/**
	* Get Session by Id.
	*
	* @param integer $id
	* @return  $session
	*/
	public function getOne($id)
	{
		return $this->session->find($id);
	}

	/**
    * Get all active sessions
    *
    * @return sessions
    */
	public function getAllActiveSessions()
	{
		return $this->session
					->where('end_datetime', '>', Carbon::now())
					->orWhere('end_datetime', null)
					->with(['customer', 'game'])->get();
	}

	/**
    * Get all history sessions
    *
    * @return sessions
    */
	public function getAllHistorySessions()
	{
		return $this->session
					->where('end_datetime', '<=', Carbon::now())
					->orderBy('end_datetime', 'desc')
					->with(['customer', 'game'])->get();
	}

	/**
    * Get all sessions
    *
    * @return sessions
    */
	public function getAllSessions()
	{
		return $this->session
					->with(['customer', 'game'])->get();
	}

	/**
    * Get user's active sessions
    *
    * @return sessions
    */
	public function getUserActiveSessions($userId)
	{
		return $this->session
					->where('end_datetime', '>', Carbon::now())
					->orWhere('end_datetime', null)
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->with(['customer', 'game'])->get();
	}

	/**
    * Get user's history sessions
    *
    * @return sessions
    */
	public function getUserHistorySessions($userId)
	{
		return $this->session
					->where('end_datetime', '<=', Carbon::now())
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})
					->orderBy('end_datetime', 'desc')
					->with(['customer', 'game'])->get();
	}

	/**
    * Get user's sessions
    *
    * @return sessions
    */
	public function getUserSessions($userId)
	{
		return $this->session
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})
					->orderBy('end_datetime', 'desc')
					->with(['customer', 'game'])->get();
	}

	/**
    * Get active sessions by locetion id
    *
    * @return sessions
    */
	public function getLocationActiveSessions($locationId)
	{
		return $this->session
					->where('end_datetime', '>', Carbon::now())
					->orWhere('end_datetime', null)
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})
					->with(['customer', 'game'])->get();
	}

	/**
    * Get user's history sessions
    *
    * @return sessions
    */
	public function getLocationHistorySessions($locationId)
	{
		return $this->session
					->where('end_datetime', '<=', Carbon::now())
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})
					->orderBy('end_datetime', 'desc')
					->with(['customer', 'game'])->get();
	}

	/**
    * Get sessions by locetion id
    *
    * @return sessions
    */
	public function getLocationSessions($locationId)
	{
		return $this->session
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})
					->with(['customer', 'game'])->get();
	}

	/**
    * Update session by id
    *
    * @return session
    */
	public function update($id, $data)
	{
		return $this->session->where('id', $id)->update($data);
	}

	/**
    * Delete session by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->session->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}