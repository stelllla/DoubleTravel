<?php

require_once ('model/User.php');
require_once ('db/IDbStore.php');
require_once ('db/SQLiteStore.php');

	$countries = file_get_contents('https://restcountries.eu/rest/v1/all');
	echo $countries;
?>