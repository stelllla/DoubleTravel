<?php
/**
 * Initially bootstrap the application:
 *  - create blank database
 *
 * @author kamenim
 * @date 07/08/16
 */

require_once ('../model/User.php');
require_once ('../db/IDbStore.php');
require_once ('../db/SQLiteStore.php');


class AppSetup
{
	public function main()
	{
		$dbDriverName = Config::get('db.driver', 'SQLiteStore');
		/** @var SQLiteStore $dbDriver */
		$dbDriver = new $dbDriverName;

		$storeLocation = VAR_DIR . \Config::get('db.store.name') . '.db';

		$this->p_msg("Provision database: $storeLocation");
		if (!$dbDriver->provisionDB()) {
			$this->p_msg("ERROR: Failed to provision blank database.");
			return;
		}

		$this->p_msg("All done");
	}

	public function p_msg($msg)
	{
		echo $msg . PHP_EOL;
	}

}

$app = new AppSetup();
$app->main();