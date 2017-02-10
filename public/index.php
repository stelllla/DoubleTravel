<?php

require_once ('../model/User.php');
require_once ('../model/Country.php');
require_once ('../db/IDbStore.php');
require_once ('../db/SQLiteStore.php');

	$allCountriesData = file_get_contents('https://restcountries.eu/rest/v1/all');
	$countries = json_decode($allCountriesData, true);
	$selectedCountry = new Country([]);
	$options = "";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$index = $_POST['countries'];
		$selectedCountry = new Country($countries[$index]);
		$relatedCountries = file_get_contents('https://restcountries.eu/rest/v1/region/' . $selectedCountry->region);

		$relatedCountriesList = "";
		for ($i = 0; $i < count($relatedCountries); $i++) {
			$relCountry = new Country($relatedCountries[$i]);
			$relatedCountriesList .= "<li>{$relCountry->name},{$relCountry->subregion}</li>";
		}
	}

	for ($i = 0; $i < count($countries); $i++) {
		$country = new Country($countries[$i]);

		$options .= "<option value={$i}";
		if ($country->name == $selectedCountry->name) {
			$options .= " selected ";
		}
		$options .= ">{$country->name}</option>";
	}

//	if (!empty($REQUEST['action'])) {
//		$selectedCountry = $countries[0];
//	}

	include('../views/index.html');
