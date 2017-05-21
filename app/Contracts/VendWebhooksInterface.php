<?php 

namespace App\Contracts;

interface VendWebhooksInterface
{
	/**
    * Create vendWebhook
    *
    * @return vendWebhook
    */
    public function create($data);

    /**
    * Get vendWebhook by Id.
    *
    * @param integer $id
    * @return  $vendWebhook
    */
    public function getOne($id);

    /**
    * Get vendWebhooks.
    *
    * @return  $vendWebhooks
    */
    public function getAll();

    /**
    * Update vendWebhook.
    *
    * @param int $id
    * @param array $dataArray
    * @return  $updatevendWebhook
    */
    public function update($id, $dataArray);

    /**
    * Delete vendWebhook by id
    *
    * @param integer $id
    * @return delete
    */
    public function delete($id);
}