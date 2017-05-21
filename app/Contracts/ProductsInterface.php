<?php 

namespace App\Contracts;

interface ProductsInterface
{
    /**
    * Get product by pos_id.
    *
    * @param integer $posId
    * @return  $product
    */
    public function getByPosId($posId);
    
	/**
    * Create product
    *
    * @return product
    */
    public function create($data);

    /**
    * Get products by sessionId.
    *
    * @param integer $sessionId
    * @return  $products
    */
    public function getBySessionId($sessionId);
    
    /**
    * Get products.
    *
    * @return  $products
    */
    public function getAll();

    /**
	* Update product.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateproduct
	*/
	public function update($id, $dataArray);

	/**
    * Delete product by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id);
}