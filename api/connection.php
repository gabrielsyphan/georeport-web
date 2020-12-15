<?php
	$config["MySQL"]["host"] = "localhost";
	$config["MySQL"]["username"] = "syphan49_gabriel";
	$config["MySQL"]["password"] = "dt0a3m4a1x";
	
	$config["MySQL"]["database"] = "syphan49_georeports";
	
	$connection = mysqli_connect($config["MySQL"]["host"], $config["MySQL"]["username"], $config["MySQL"]["password"]);
	mysqli_select_db($connection, $config["MySQL"]["database"]);
?>