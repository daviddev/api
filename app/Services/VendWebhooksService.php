<?php 

namespace App\Services;

use App\Contracts\VendWebhooksInterface;
use App\VendWebhook;

class VendWebhooksService implements VendWebhooksInterface
{
	/**
	* Create a new instance of VendWebhooksService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->vendWebhook = new VendWebhook();
	}

	/**
    * Create vendWebhook
    *
    * @return vendWebhook
    */
	public function create($data)
	{
		return $this->vendWebhook->create($data);
	}

	/**
	* Get vendWebhook by Id.
	*
	* @param integer $id
	* @return  $vendWebhook
	*/
	public function getOne($id)
	{
		return $this->vendWebhook->find($id);
	}

	/**
	* Get vendWebhooks.
	*
	* @return  $vendWebhooks
	*/
	public function getAll()
	{
		return $this->vendWebhook->get();
	}

	/**
	* Update vendWebhook.
	*
	* @param int $id
	* @param array $dataArray
	* @return  $updatevendWebhook
	*/
	public function update($id, $dataArray)
	{
		return $this->vendWebhook->find($id)->update($dataArray);
	}

	/**
    * Delete vendWebhook by id
    *
    * @param integer $id
    * @return delete
    */
	public function delete($id)
	{
		return $this->vendWebhook->where('id', $id)->update(['deleted_at' => \Carbon\Carbon::now()]);
	}
}