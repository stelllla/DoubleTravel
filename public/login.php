<?php

require_once ('model/User.php');
require_once ('db/IDbStore.php');
require_once ('db/SQLiteStore.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = $_POST['email'];
	$password = $_REQUEST['password'];

	$dbStore = new SQLiteStore();
	$currUser = $dbStore->loadUser($email);
	if ($currUser->PASS == $password) {
		header('index.html');
	}
}
