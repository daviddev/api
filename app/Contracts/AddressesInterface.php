<?php 

namespace App\Contracts;

interface AddressesInterface
{
	/**
    * Create address
    *
    * @return address
    */
    public function create($data);

    /**
    * Get address by phone or address1.
    *
    * @param integer $phone
    * @param string $address
    * @return  $address
    */
    public function getByPhoneOrAddress($phone, $address);

    /**
    * Get address by Id.
    *
    * @param integer $id
    * @return  $address
    */
    public function getOne($id);
    
    /**
    * Get addresses.
    *
    * @return  $addresses
    */
    public function getAll();

    /**
	* Update address.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateAddress
	*/
	public function update($id, $dataArray);

	/**
    * Delete address by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id);
}