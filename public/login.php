<?php

require_once ('../model/User.php');
require_once ('../db/SQLiteStore.php');

// start session
session_start();
// if we have valid user, go to index
if (!empty($_SESSION['user_mail'])) {
	header('location: /');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = $_POST['EMAIL'];
	$password = $_REQUEST['PASS'];

	$dbStore = new SQLiteStore();

	if(key_exists('FIRST_NAME', $_POST) && key_exists('LAST_NAME', $_POST)) {
		$userToInsert = new User($_POST);
		$dbStore->saveUser($userToInsert);
	}

	$currUser = $dbStore->loadUser($email);
	if ($currUser && $currUser->PASS == $password) {
		// store logged in user
		$_SESSION['user_mail'] = $email;
	}


	// go to index
	header('location: /');
	exit();
}


include('../views/login.html');
