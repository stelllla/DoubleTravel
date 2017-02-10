<?php
require_once ('../db/SQLiteStore.php');


class AppSetup
{
	public function main()
	{
		/** @var SQLiteStore $dbDriver */
		$dbDriver = new SQLiteStore();

		$this->p_msg("Provision database ");
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
