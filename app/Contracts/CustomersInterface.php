<?php 

namespace App\Contracts;

interface CustomersInterface
{
	/**
	* Get Customer by pos_id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getByPosId($posId);
	
	/**
	* Get Customer by email or pos_id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getByEmailOrPos($email, $posId);
	
	/**
	* Get Customer by Id.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getOne($id);

	/**
    * Get all customers
    *
    * @return customers
    */
	public function getAllCustomers($isAjax);

	/**
    * Get customers by user id
    *
    * @return customers
    */
	public function getUserCustomers($userId, $isAjax);
	
	/**
    * Get customers by location id
    *
    * @return customers
    */
	public function getLocationCustomers($locationId, $isAjax);

	/**
	* Get Customer by Id for edit.
	*
	* @param integer $id
	* @return  $customer
	*/
	public function getCustomerEdit($id);
	
	/**
	* Create Customer.
	*
	* @param array $dataArray
	* @return  $createJob
	*/
	public function createOne($dataArray);

	/**
    * Get all customers
    *
    * @return customers
    */
	public function getAll();

	/**
	* Update customer.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateJob
	*/
	public function update($id, $dataArray);

	/**
    * Delete customer by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id);
}