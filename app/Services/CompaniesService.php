<?php 

namespace App\Services;

use App\Contracts\CompaniesInterface;
use App\Company;

class CompaniesService implements CompaniesInterface
{
	/**
	* Create a new instance of CompaniesService class
	*
	* @return void
	*/
	public function __construct()
	{
		$this->company = new Company();
	}

	/**
    * Get user's companies
    *
    * @return companies
    */
    public function getUserCompanies($userId)
    {
        return $this->company
                    ->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', $userId);
                    })->get();
    }

    /**
    * Get all companies
    *
    * @return companies
    */
    public function getAll()
    {
    	return $this->company->get();
    }
}