<?php

require_once ('../model/User.php');
require_once ('../model/Country.php');
require_once ('../db/SQLiteStore.php');

	// start session
	session_start();

	// check if we have valid user
	if (empty($_SESSION['user_mail'])) {
		header('location: /login.php');
		exit();
	}

	if (isset($_GET['logout'])) {
		$_SESSION['user_mail'] = '';
		header('location: /login.php');
	}

	$dbStore = new SQLiteStore();
	$user = $dbStore->loadUser($_SESSION['user_mail']);

	$allCountriesData = file_get_contents('https://restcountries.eu/rest/v1/all');
	$countries = json_decode($allCountriesData, true);
	$selectedCountry = new Country([]);
	$options = "";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$index = $_POST['countries'];
		if (!is_numeric($index) || $index < 0) {
			header('location: /index.php');
			exit();
		}
		$selectedCountry = new Country($countries[$index]);
		$subregion = str_replace(' ', '%20', $selectedCountry->subregion);
		$relatedCountries = json_decode(file_get_contents('https://restcountries.eu/rest/v1/subregion/' . $subregion), true);

		$relatedCountriesList = "";
		for ($i = 0; $i < count($relatedCountries); $i++) {
			$relCountry = new Country($relatedCountries[$i]);
			$relatedCountriesList .= "<li>{$relCountry->name}</li>";
		}

		// get whether
		//$apikey = "ea9aaebdbadf339a42046a3a38a53e67";
		$weatherUrl = "http://api.openweathermap.org/data/2.5/forecast/daily?lat=". $selectedCountry->latlng[0] . "&lon=". $selectedCountry->latlng[1] . "&appid=ea9aaebdbadf339a42046a3a38a53e67&cnt=5";
		$weatherContent = json_decode(file_get_contents($weatherUrl));
		$whetherTable = "";
		$time = time();
		$weatherDays = $weatherContent->list;
			$whetherTable .= "<tr>";
				for ($i = 0; $i < 5; $i++) {
					$whetherTable .= "<th>" . date("Y-m-d", mktime(0,0,0,date("n", $time),date("j",$time) + $i +1 ,date("Y", $time))) . "</th>";
				}
			$whetherTable .= "</tr>";
			$whetherTable .= "<tr>";
				for ($i = 0; $i < count($weatherDays); $i++) {
					$weatherIcon = "http://openweathermap.org/img/w/".$weatherDays[$i]->weather[0]->icon.".png";
					$whetherTable .= "<th>";
					$whetherTable .= "".$weatherDays[$i]->weather[0]->main."<br>";
					$whetherTable .= "<div><img src=".$weatherIcon."></div>";
					$whetherTable .= "Temp: ".round(($weatherDays[$i]->temp->day - 273.15),1)." C.<br>";
					$whetherTable .= "Humidity: ".$weatherDays[$i]->humidity."<br>";
					$whetherTable .= "</th>";
				}
		$whetherTable .= "</tr>";
	}

	for ($i = 0; $i < count($countries); $i++) {
		$country = new Country($countries[$i]);

		$options .= "<option value={$i}";
		if ($country->name == $selectedCountry->name) {
			$options .= " selected ";
		}
		$options .= ">{$country->name}</option>";
	}

	include('../views/index.html');
