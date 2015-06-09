<?php 
	require("../vendor/autoload.php");

	use Goutte\Client;

	$client = new Client();

	$crwaler = $client -> request("GET","http://www.carsales.com.au/cars/results?q=(((Make%3D%5BSubaru%5D%7B%26%7DModel%3D%5BOutback%5D)%26(Year%3Drange%5B2012..%5D%26Odometer%3Drange%5B0..60000%5D))%26Service%3D%5BCarsales%5D)&sortby=~Price&cpw=1&limit=20");

	// Get the latest post in this category and display the titles
	$crwaler->filter('.result-item h2 a')->each(function ($node) {
	    print $node->text()."\n";
	});
?>