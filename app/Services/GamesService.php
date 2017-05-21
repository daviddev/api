<?php 

namespace App\Services;

use App\Contracts\GamesInterface;
use App\Game;

use Carbon\Carbon;

class GamesService implements GamesInterface
{
	/**
	* Create a new instance of GamesService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->game = new Game();
	}

	/**
    * Get all games
    *
    * @return games
    */
	public function getAllGames($userId)
	{
		return $this->game->with(['location', 'session'])->get();
	}

	/**
    * Get user's games
    *
    * @return games
    */
	public function getUserGames($userId)
	{
		return $this->game
					->whereHas('location', function($locationQuery) use ($userId) {
						$locationQuery->whereHas('company', function ($query) use ($userId) {
							$query->whereHas('user', function($q) use ($userId) {
								$q->where('users.id', $userId);
							});
						});
					})->with(['location', 'session'])->get();
	}

	/**
    * Get location's games
    *
    * @return games
    */
	public function getLocationGames($locationId)
	{
		return $this->game
					->whereHas('location', function($query) use ($locationId) {
						$query->whereId($locationId);
					})->with(['location', 'session'])->get();
	}

	/**
    * Create game
    *
    * @return game
    */
	public function createOne($data)
	{
		return $this->game->create($data);
	}

	/**
    * Get game by id
    *
    * @return game
    */
	public function getOne($id)
	{
		return $this->game->with(['session', 'location'])->find($id);
	}

	/**
    * Get game by id for edit
    *
    * @return game
    */
	public function getGameEdit($id)
	{
		return $this->game->find($id);
	}

	/**
    * Update game by id
    *
    * @return game
    */
	public function update($id, $data)
	{
		return $this->game->where('id', $id)->update($data);
	}

	/**
    * Delete game by id
    *
    * @return game
    */
	public function delete($id)
	{
		return $this->game->find($id)->update(['deleted_at' => Carbon::now()]);
	}
}