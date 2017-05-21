<?php 

namespace App\Contracts;

interface CompaniesInterface
{
	/**
    * Get user's companies
    *
    * @return companies
    */
    public function getUserCompanies($userId);

	/**
    * Get all companies
    *
    * @return companies
    */
    public function getAll();
}