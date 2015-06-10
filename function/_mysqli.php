<?php 
	/**
	 * Will need:
	 *	- DATABASE_IP
	 *	- DATABASE_USERNAME
	 *	- DATABASE_PASSWORD
	 *	- DATABASE_DATABASE
	 */

	$db = new mysqli(DATABASE_IP,DATABASE_USERNAME,DATABASE_PASSWORD,DATABASE_DATABASE);

	if ($db->connect_errno) {
		echo "Failed to connect to MySQL: (".$db->connect_errno.") ".$db->connect_error;
	}

	

?>