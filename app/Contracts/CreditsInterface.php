<?php 

namespace App\Contracts;

interface CreditsInterface
{
	/**
    * Create address
    *
    * @return address
    */
    public function create($data);

    /**
    * Get credit by order id
    *
    * @return credit
    */
    public function getByOrderId($orderId);

    /**
    * Get all credits
    *
    * @return credits
    */
    public function getAllCredits();

    /**
    * Get credits by user id
    *
    * @return credits
    */
	public function getUserCredits($userId);

    /**
    * Get credits by location id
    *
    * @return credits
    */
    public function getLocationCredits($locationId);

    /**
    * Get credit by Id.
    *
    * @param integer $id
    * @return  $credit
    */
    public function getOne($id);

    /**
    * Get credits.
    *
    * @return  $credits
    */
    public function getAll();

    /**
    * Update credit by id
    *
    * @return credit
    */
    public function update($id, $data);

    /**
    * Delete credit by id
    *
    * @param integer $id
    * @return delete
    */
    public function delete($id);
}