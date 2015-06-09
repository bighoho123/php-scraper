<?php 
	require("../vendor/autoload.php");

	use Goutte\Client;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Scraper</title>
</head>
<body>
	<p>

<?php
	$client = new Client();

	$crwaler = $client -> request("GET","http://www.carsales.com.au/cars/results?q=(((Make%3D%5BSubaru%5D%7B%26%7DModel%3D%5BOutback%5D)%26(Year%3Drange%5B2012..%5D%26Odometer%3Drange%5B0..60000%5D))%26Service%3D%5BCarsales%5D)&sortby=~Price&cpw=1&limit=20");

	// Get title
	$crwaler->filter('.result-item')->each(function ($node) {
		/* Title & Link */
		$node->filter("h2 a")->each(function($node_1){
		    print trim(preg_replace("/<span.*span>/s","",$node_1->html()))."<br>";
		    print "http://www.carsales.com.au".$node_1->attr("href")."<br>";
		});
		/* Price */
		$node->filter(".additional-information .price a")->each(function($node_2){
		    print $node_2->attr('data-price')."<br>";
		});
		/* Odometer */
		$node->filter(".vehicle-features .item-odometer")->each(function($node_2){
		    print preg_replace(array("/<i.*i>/","/,/","/ km$/"),array("","",""),$node_2->html())."<br>";
		});
	    echo "<hr>";
	});
?>
	</p>
</body>
</html>