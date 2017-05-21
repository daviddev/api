<?php 

namespace App\Contracts;

interface GamesInterface
{
    /**
    * Get all games
    *
    * @return games
    */
    public function getAllGames($userId);
    
	/**
    * Get user's games
    *
    * @return games
    */
	public function getUserGames($userId);

    /**
    * Create game
    *
    * @return game
    */
    public function createOne($data);

	/**
    * Get game by id
    *
    * @return game
    */
	public function getOne($id);

    /**
    * Get game by id for edit
    *
    * @return game
    */
    public function getGameEdit($id);

    /**
    * Update game by id
    *
    * @return game
    */
    public function update($id, $data);

    /**
    * Delete game by id
    *
    * @return game
    */
    public function delete($id);
}