<?php 

namespace App\Services;

use App\Contracts\UsersInterface;
use App\User;

use Carbon\Carbon;

class UsersService implements UsersInterface
{
	/**
	* Create a new instance of UsersService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->user = new User();
	}

	/**
    * Get all users
    *
    * @return users
    */
	public function getAll()
	{
		return $this->user->with(['company', 'location'])->get();
	}

	/**
    * Create user
    *
    * @return user
    */
	public function createOne($data)
	{
		if (count($data['companies']) > 0) {
            $companies = $data['companies'];
            unset($data['companies']);
            $data['location_id'] = null;
        	$data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data);
            $user->company()->attach($companies);
            return $user;
        }
        $data['password'] = bcrypt($data['password']);

        return $this->user->create($data);
	}

	/**
    * Get user by id
    *
    * @return user
    */
	public function getOne($id)
	{
		return $this->user->find($id);
	}

	/**
    * Get user by id for edit
    *
    * @return user
    */
	public function getEdit($id)
	{
		return $this->user->find($id);
	}

	/**
    * Update user by id
    *
    * @return user
    */
	public function update($id, $data)
	{
		$user = $this->user->where('id', $id)->first();
		$user->company()->detach();
		if ($data['role'] == 'admin') {
			$user->company()->attach($data['companies']);
			$data['location_id'] = null;
		}

		if (isset($data['password']) && $data['password'] != '')
        	$data['password'] = bcrypt($data['password']);

		unset($data['companies']);
		unset($data['company']);
		unset($data['location']);

		return $this->user->where('id', $id)->update($data);
	}

	/**
    * Delete user by id
    *
    * @return user
    */
	public function delete($id)
	{
		$user = $this->user->find($id);
		$user->company()->detach();
		return $user->delete();
	}
}