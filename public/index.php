<?php 
	date_default_timezone_set('UTC');
	set_time_limit("600");

	require_once("../config.php");
	require_once("../vendor/autoload.php");
	require_once("../function/_mysqli.php");
	require_once("../function/_functions.php");

	use Goutte\Client;

?>
<?php
	$logger = new Katzgrau\KLogger\Logger(__DIR__.'/../logs');

	$batchId = insertThisBatch($db);
	$logger->info("Batch Job {$batchId} started");

	try {
		$client = new Client();
		$crawler = $client -> request("GET","http://www.carsales.com.au/cars/results?q=((((Make%3d%5bSubaru%5d%7b%26%7dModel%3d%5bOutback%5d)%26(Year%3drange%5b2012..%5d%26Odometer%3drange%5b0..60000%5d))%26Service%3d%5bCarsales%5d)%26((((SiloType%3d%5bDealer+used+cars%5d)%7c(SiloType%3d%5bDemo+and+near+new+cars%5d))%7c(SiloType%3d%5bPrivate+seller+cars%5d))))&sortby=~Price&limit=20&cpw=1");

		getInfomationOnThisPage($crawler,$db,$batchId);

		$next=$crawler->filter("#ctl09_p_ctl14_ctl01_headerPagination_hlNextLink");
		while (count($next)>0) {
			$link=$next->link();
			$crawler=$client->click($link);
			getInfomationOnThisPage($crawler,$db,$batchId);
			$next=$crawler->filter("#ctl09_p_ctl14_ctl01_headerPagination_hlNextLink");
		}
	} catch (Exception $e) {
		$logger->error($e->getMessage());
	}

	$logger->info("Batch Job {$batchId} finished");
	
?>