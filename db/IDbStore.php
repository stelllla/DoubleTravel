<?php

require_once ('../model/User.php');

interface IDbStore
{
	/**
	 * @param int $id
	 * @return User|null
	 */
	public function loadUser($id);
}