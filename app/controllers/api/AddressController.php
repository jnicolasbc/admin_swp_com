<?php namespace Api\V1;
use Doctor,Input;

class AddressController extends \Controller { 

	public function __construct()
	{
		 
	}

	public function getIndex()
	{
		return Helpers::countriesAddress();
	}

	public function getProvincies($id)
	{
		return Helpers::provinciesAddress($id);
	}

	public function getCountries()
	{
		$term = Input::get('term');
		return Helpers::countriesAddress($term);
	}

	public function getCountries2()
	{
		$term = Input::get('term');
		return Helpers::countriesAddress2($term);
	}
	
	public function getCiudades($pro)
	{		 
		return Helpers::citiesAddress($pro);
	}
	public function getCities2()
	{
		$term = Input::get('term');
		$country = Input::get('country');
		return Helpers::citiesAddress2($term,$country);
	}

	public function getProvincies2()
	{
		$country = Input::get('country');
		$load = Input::get('load');
		$id = Input::get('id');
		return Helpers::provinciesAddress2($id,$load,$country);
	}

	public function getCities3()
	{
		$country = Input::get('country');
		$id = Input::get('id');
		$load = Input::get('load');
		return Helpers::citiesAddress3($id,$load,$country);
	}
}