<?php 

namespace App\Contracts;

interface UsersInterface
{
    /**
    * Get all users
    *
    * @return users
    */
    public function getAll();

    /**
    * Create user
    *
    * @return user
    */
    public function createOne($data);

	/**
    * Get user by id
    *
    * @return user
    */
	public function getOne($id);

    /**
    * Get user by id for edit
    *
    * @return user
    */
    public function getEdit($id);

    /**
    * Update user by id
    *
    * @return user
    */
    public function update($id, $data);

    /**
    * Delete user by id
    *
    * @return user
    */
    public function delete($id);
}