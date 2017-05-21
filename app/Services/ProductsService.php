<?php 

namespace App\Services;

use App\Contracts\ProductsInterface;
use App\Product;

class ProductsService implements ProductsInterface
{
	/**
	* Create a new instance of ProductsService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->product = new Product();
	}

	/**
	* Get product by pos_id.
	*
	* @param integer $posId
	* @return  $product
	*/
	public function getByPosId($posId)
	{
		return $this->product->where('pos_id', $posId)->first();
	}

	/**
    * Create product
    *
    * @return product
    */
	public function create($data)
	{
		return $this->product->create($data);
	}

	/**
	* Get products by sessionId.
	*
	* @param integer $sessionId
	* @return  $products
	*/
	public function getBySessionId($sessionId)
	{
		return $this->product->where('session_id', $sessionId)->get();
	}

	/**
	* Get productes.
	*
	* @return  $productes
	*/
	public function getAll()
	{
		return $this->product->get();
	}

	/**
	* Update product.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updateproduct
	*/
	public function update($id, $dataArray)
	{
		return $this->product->find($id)->update($dataArray);
	}

	/**
    * Delete product by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->product->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}