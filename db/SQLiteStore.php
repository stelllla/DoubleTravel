<?php

require_once ('../model/User.php');

class SQLiteStore
{

	/** @var SQLite3 */
	private $db;

	function __construct($location = '')
	{
		if (empty($location)) {
			// fallback to default location. eg. VAR_DIR/profiles.db
			$location = __DIR__ . '/my.db';
		}
		if (empty($this->db)) {
			$this->db = new SQLite3($location);
		}
	}

	function __destruct()
	{
		if ($this->db) {
			$this->db->close();
		}
	}

	/**
	 * @return bool <b>TRUE</b> if the query succeeded, <b>FALSE</b> on failure.
	 */
	public function provisionDB()
	{
		$sqlDir = __DIR__ . '/sql/';

		// empty and delete DB tables
		try {
			$sql = file_get_contents($sqlDir . 'resetdb.sql');
			$res = @$this->db->exec($sql);
		} catch (Exception $e) {
			// probably, no such table, just go on
			error_log('Warning: Failed to reset DB tables');
		}

		// create Schema
		$sql = file_get_contents($sqlDir . 'schema.sql');
		$res = $this->db->exec($sql);

		// provision database
		$sql = file_get_contents($sqlDir . 'provision.sql');
		$res = $this->db->exec($sql);

		return $res;
	}

	private function _userFromRow($row)
	{
		if (!is_array($row)) {
			return null;
		}
		return new User($row);
	}


	/**
	 * @param int $id
	 * @return User|null
	 */
	public function loadUser($email)
	{
		$email = trim(strtolower($email));
		if (empty($email)) {
			return null;
		}

		$stmt = $this->db->prepare("SELECT * FROM USERS_ WHERE EMAIL = :EMAIL");
		$stmt->bindValue(':EMAIL', $email, SQLITE3_TEXT);
		$res = $stmt->execute();
		if ($res === false) {
			return null;
		}
		return $this->_userFromRow($res->fetchArray());
	}
}